<?php

namespace Tests\Feature;

use App\Classes\CustomEncrypter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_encrypt_key()
    {
        $keypair = CustomEncrypter::getKeyPairForTests();
        $publicPem = $keypair['publicPem'];

        $response = $this->postJson('/api/encryptkey', [
            'publicKey' => $publicPem
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'key_id',
                'encryptedKey',
            ]);
    }
}
