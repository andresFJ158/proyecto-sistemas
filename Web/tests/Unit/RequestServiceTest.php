<?php

namespace Tests\Unit;

use App\Services\RequestService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_completes_the_valid_workflow_and_records_closure(): void
    {
        $student = $this->user();
        $admin = $this->user('ADMIN');
        $request = $this->serviceRequest($student);
        $service = app(RequestService::class);

        $request = $service->assign($request, $admin, $admin);
        $request = $service->changeStatus($request, 'EN_PROCESO', $admin);
        $request = $service->changeStatus($request, 'RESUELTA', $admin);
        $request = $service->changeStatus($request, 'CERRADA', $admin);

        $this->assertSame('CERRADA', $request->status);
        $this->assertNotNull($request->closed_at);
        $this->assertSame(4, $request->statusHistory()->count());
    }
}
