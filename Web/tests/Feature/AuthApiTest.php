<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_login_and_get_profile(): void
    {
        $user = $this->user();
        $login = $this->postJson('/api/v1/login', ['email' => $user->email, 'password' => 'password', 'device_name' => 'test']);
        $login->assertOk()->assertJsonStructure(['token', 'user' => ['id', 'email', 'role']]);

        $this->withToken($login->json('token'))->getJson('/api/v1/me')->assertOk()->assertJsonPath('data.email', $user->email);
    }

    public function test_login_rejects_invalid_password(): void
    {
        $user = $this->user();
        $this->postJson('/api/v1/login', ['email' => $user->email, 'password' => 'incorrecta'])->assertUnprocessable();
    }
}
