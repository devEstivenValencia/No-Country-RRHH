<?php

namespace Tests\Feature;

use App\Classes\CustomEncrypter;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_employee_can_be_created(): void
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
        $companyData = [
            'key_id' => $keyId,
            'company_name' => 'Test Employee',
            'credentials' =>
            [
                'email' => CustomEncrypter::encryptOpenSSL('testCreated@example.com', $sharedKey),
                'password' => CustomEncrypter::encryptOpenSSL('12Jhasass85@', $sharedKey)
            ],
            'contact' => [
                'email' => CustomEncrypter::encryptOpenSSL('testCreated@example.com', $sharedKey),
                'phone' => CustomEncrypter::encryptOpenSSL('11111111', $sharedKey)
            ]
        ];

        $response = $this->postJson('/api/account/enterprise/register', $companyData);
        $company_id = $response['user']['id'];

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

        $employeeData = [
            'key_id' => $keyId,
            'company_id' => CustomEncrypter::encryptOpenSSL($company_id, $sharedKey),
            'employee' => [
                'name' => 'Juan',
                'dni' => CustomEncrypter::encryptOpenSSL("123654874", $sharedKey),
                'address' => [
                    'country' => CustomEncrypter::encryptOpenSSL("Argentina", $sharedKey),
                    'province' => CustomEncrypter::encryptOpenSSL("San Luis", $sharedKey),
                    'city' => CustomEncrypter::encryptOpenSSL("Capital", $sharedKey),
                    'address' => CustomEncrypter::encryptOpenSSL("Esteban Roque 1236", $sharedKey),
                    'zipcode' => CustomEncrypter::encryptOpenSSL("5700", $sharedKey),
                ],
                'admission_date' => '2024-04-20',
                'role' => 'Supervisor',
                'contact' => [
                    'email' => CustomEncrypter::encryptOpenSSL("employeeTest@mail.com", $sharedKey),
                    'phone' => CustomEncrypter::encryptOpenSSL("12577895", $sharedKey)
                ],
                'credentials' => [
                    'email' => CustomEncrypter::encryptOpenSSL("EmployeeMail@test.com", $sharedKey),
                    'password' => CustomEncrypter::encryptOpenSSL("125Rt@fdafaf", $sharedKey)
                ]
            ]
        ];

        $responseUpdate = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->postJson('/api/enterprise/employee/create', $employeeData);

        $responseUpdate->assertStatus(200)
            ->assertJson([
                'success' => true,

            ]);
    }
}
