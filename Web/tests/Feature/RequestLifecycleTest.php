<?php

namespace Tests\Feature;

use App\Models\RequestStatusHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RequestLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_create_a_request_with_initial_history(): void
    {
        $student = $this->user();
        Sanctum::actingAs($student);

        $response = $this->postJson('/api/v1/requests', [
            'request_type_id' => $this->requestType()->id,
            'title' => 'Proyector sin señal',
            'description' => 'El proyector enciende pero no detecta ninguna entrada.',
            'priority' => 'ALTA',
            'location' => 'Aula 20',
        ]);

        $response->assertCreated()->assertJsonPath('data.status', 'PENDIENTE');
        $this->assertDatabaseHas('request_status_history', ['request_id' => $response->json('data.id'), 'new_status' => 'PENDIENTE']);
    }

    public function test_student_only_sees_own_requests(): void
    {
        $student = $this->user();
        $other = $this->user();
        $request = $this->serviceRequest($other);
        Sanctum::actingAs($student);

        $this->getJson("/api/v1/requests/{$request->id}")->assertForbidden();
        $this->getJson('/api/v1/requests')->assertOk()->assertJsonCount(0, 'data');
    }

    public function test_owner_can_update_and_delete_a_pending_request(): void
    {
        $student = $this->user();
        $request = $this->serviceRequest($student);
        Sanctum::actingAs($student);

        $this->putJson("/api/v1/requests/{$request->id}", ['title' => 'Título actualizado'])
            ->assertOk()->assertJsonPath('data.title', 'Título actualizado');
        $this->deleteJson("/api/v1/requests/{$request->id}")->assertOk();
        $this->assertSoftDeleted('requests', ['id' => $request->id]);
    }

    public function test_admin_can_assign_and_advance_request_with_traceability(): void
    {
        $student = $this->user();
        $admin = $this->user('ADMIN');
        $request = $this->serviceRequest($student);
        Sanctum::actingAs($admin);

        $this->patchJson("/api/v1/requests/{$request->id}/assign", ['assigned_to' => $admin->id])
            ->assertOk()->assertJsonPath('data.status', 'ASIGNADA');
        $this->patchJson("/api/v1/requests/{$request->id}/status", ['status' => 'EN_PROCESO', 'comment' => 'Diagnóstico iniciado.'])
            ->assertOk()->assertJsonPath('data.status', 'EN_PROCESO');

        $this->assertSame(2, RequestStatusHistory::where('request_id', $request->id)->count());
        $this->assertDatabaseHas('request_status_history', ['request_id' => $request->id, 'new_status' => 'EN_PROCESO', 'comment' => 'Diagnóstico iniciado.']);
    }

    public function test_status_transition_cannot_skip_the_workflow(): void
    {
        $admin = $this->user('ADMIN');
        $request = $this->serviceRequest($this->user());
        Sanctum::actingAs($admin);

        $this->patchJson("/api/v1/requests/{$request->id}/status", ['status' => 'RESUELTA'])->assertUnprocessable();
        $this->assertSame('PENDIENTE', $request->fresh()->status);
    }

    public function test_student_and_admin_can_exchange_comments(): void
    {
        $student = $this->user();
        $request = $this->serviceRequest($student);
        Sanctum::actingAs($student);

        $this->postJson("/api/v1/requests/{$request->id}/comments", ['body' => '¿Cuándo pueden revisar el aula?'])->assertCreated();
        $this->getJson("/api/v1/requests/{$request->id}/comments")->assertOk()->assertJsonCount(1, 'data');
    }
}
