<?php

namespace Tests\Feature;

use App\Classes\CustomEncrypter;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_company_can_be_created()
    {
        $keypair = CustomEncrypter::getKeyPairForTests();
        $publicPem = $keypair['publicPem'];
        $privatePem = $keypair['privatePem'];

        // Obtener la clave dinámica para encriptar la información sensible
        $encryptionKeyResponse = $this->postJson('/api/encryptkey', [
            'publicKey' => $publicPem
        ]);

        $keyId = $encryptionKeyResponse["key_id"];
        $encryptedKey = $encryptionKeyResponse["encryptedKey"];
        openssl_private_decrypt(base64_decode($encryptedKey), $sharedKey, $privatePem, OPENSSL_PKCS1_PADDING);

        // Enviamos una solicitud para crear una compañía
        $data = [
            'key_id' => $keyId,
            'company_name' => 'Test Comany',
            'credentials' =>
            [
                'email' => CustomEncrypter::encryptOpenSSL('test12aaaaaaaaaaaaaaaa@example.com', $sharedKey),
                'password' => CustomEncrypter::encryptOpenSSL('12Jhasass85@', $sharedKey)
            ],
            'contact' => [
                'email' => CustomEncrypter::encryptOpenSSL('test12@example.com', $sharedKey),
                'phone' => CustomEncrypter::encryptOpenSSL('11111111', $sharedKey)
            ]
        ];
        $response = $this->postJson('/api/account/enterprise/register', $data);

        // Verificamos el estado de la respuesta y el contenido
        $response->assertStatus(201)
            ->assertJsonStructure([
                'session',
                'user' => [
                    'id',
                    'email',
                    'company_name',
                    'contact' => [
                        'phone',
                        'email'
                    ]
                ]
            ]);
        $token = $response['session'];

        // Obtener la clave dinámica para encriptar la información sensible
        $encryptionKeyResponse = $this->postJson('/api/encryptkey', [
            'publicKey' => $publicPem
        ]);

        $keyId = $encryptionKeyResponse["key_id"];
        $encryptedKey = $encryptionKeyResponse["encryptedKey"];
        openssl_private_decrypt(base64_decode($encryptedKey), $sharedKey, $privatePem, OPENSSL_PKCS1_PADDING);

        $dataToUpdateUser = [
            'key_id' => $keyId,
            'company_name' => 'Test Comany update',
            'contact' => [
                'email' => CustomEncrypter::encryptOpenSSL('testUpdate@example.com', $sharedKey),
                'phone' => CustomEncrypter::encryptOpenSSL('989844566', $sharedKey)
            ],
            'location' => [
                'country' => CustomEncrypter::encryptOpenSSL('Brasil', $sharedKey),
                'province' => CustomEncrypter::encryptOpenSSL('Amazonas', $sharedKey),
                'city' => CustomEncrypter::encryptOpenSSL('Rio', $sharedKey),
                'address' => CustomEncrypter::encryptOpenSSL('Calle falsa 123', $sharedKey)
            ],
            'sector' => 'Contaminación'
        ];

        $responseUpdate = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->putJson('/api/account/enterprise/modify', $dataToUpdateUser);
        /* 
        dd($responseUpdate->json(), $dataToUpdateUser); */
        $responseUpdate->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }


    // /** @test */
    // public function a_company_can_be_updated()
    // {
    //     // Create a user
    //     $user = User::factory()->create([
    //         'email' => 'test2@example.com',
    //         'password' => Hash::make('12Jhasass85@')
    //     ]);
    //
    //     // Generate Sanctum token for the user
    //     $token = $user->createToken('Test Token')->plainTextToken;


    //     $encryptionResponse = $this->postJson('/api/encrypt', [
    //         'credentials' =>
    //         [
    //             'email' => 'test23@example.com',
    //             'password' => '12Jhasass85@'
    //         ],
    //         'contact' => [
    //             'email' => 'test23@example.com',
    //             'phone' => '123456789'
    //         ]
    //     ]);



    //     // Extraes los datos encriptados de la respuesta
    //     $data = $encryptionResponse->json();
    //     // Enviamos una solicitud para crear una compañía
    //     $arrayMerge = array_merge($data, ['company_name' => 'Test2 Comany']);
    //     $response = $this->postJson('/api/account/enterprise/register', $arrayMerge);



    //     // Enviamos una solicitud para actualizar la compañía
    //     $responseUpdate = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->putJson('/api/account/enterprise/modify', [
    //         'user_id' => $response['user']['id'],
    //         'company_name' => 'Updated Company',
    //         'contact' => [
    //             'email' => 'test345@example.com',
    //             'phone_number' => '987654321'
    //         ]
    //     ]);

    //

    //     // Verificamos el estado de la respuesta y el contenido
    //     $responseUpdate->assertStatus(200)
    //         ->assertJson([
    //             'success' => true
    //         ]);
    // }
}
