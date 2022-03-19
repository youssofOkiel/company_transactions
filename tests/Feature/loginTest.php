<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

use Tests\TestCase;

class loginTest extends TestCase
{
//    use RefreshDatabase;

    public function test_user_login_api_request()
    {
//        $user = User::factory()->make();

        $response = $this->postJson('/api/login', [
            'email' => "admin@gmail.com",
            'password' => "qwer1234"
        ]);

        $response->assertStatus(200);
    }

}
