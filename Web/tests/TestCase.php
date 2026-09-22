<?php

namespace Tests;

use App\Models\RequestType;
use App\Models\Role;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function user(string $role = 'STUDENT'): User
    {
        $roleModel = Role::firstOrCreate(['name' => $role], ['label' => ucfirst(strtolower($role))]);

        return User::factory()->create(['role_id' => $roleModel->id]);
    }

    protected function requestType(): RequestType
    {
        return RequestType::firstOrCreate(['name' => 'Tecnología'], ['description' => 'Soporte']);
    }

    protected function serviceRequest(User $student, array $attributes = []): ServiceRequest
    {
        return ServiceRequest::create(array_merge([
            'tracking_code' => 'CC-TEST-'.strtoupper(fake()->unique()->bothify('####??')),
            'user_id' => $student->id,
            'request_type_id' => $this->requestType()->id,
            'title' => 'Problema de conexión',
            'description' => 'La conexión del laboratorio no funciona correctamente.',
            'priority' => 'MEDIA',
            'status' => 'PENDIENTE',
            'location' => 'Laboratorio 1',
        ], $attributes));
    }
}
