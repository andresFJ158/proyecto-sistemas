<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_dashboard_but_student_cannot(): void
    {
        $this->actingAs($this->user('ADMIN'))->get('/dashboard')->assertOk()->assertSee('Panel administrativo');

        auth()->logout();
        $this->actingAs($this->user())->get('/dashboard')->assertForbidden();
    }

    public function test_admin_can_export_the_requests_report(): void
    {
        $admin = $this->user('ADMIN');
        $this->serviceRequest($this->user());

        $this->actingAs($admin)->get('/reports/export')
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8')
            ->assertDownload('reporte-solicitudes.csv');
    }
}
