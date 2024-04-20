<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function encrypt_test()
    {
        $userData = [
            'email' => 'kyow785@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/encrypt', $userData);

        $response->assertStatus(200)
            ->assertJson([
                'email' => '...',
                'password' => '...',
                'password_confirmation' => '...',
            ]);
    }
}
