<?php

namespace Tests\Feature;

use App\Classes\CustomEncrypter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;

class EmployeeUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_employee(): void
    {
        $user_company = new User();
        $user_company->id = Str::uuid(36);
        $user_company->email = 'employee-update-company@email.com';
        $user_company->email_verified_at = now();
        $user_company->password = Hash::make('AAAbbb111222@');
        $user_company->remember_token = Str::random(10);
        $user_company->save();

        $company = new Company();
        $company->id = Str::uuid();
        $company->user_id = $user_company->id;
        $company->save();

        $user = new User();
        $user->id = Str::uuid(36);
        $user->email = 'employee-update-employee@email.com';
        $user->email_verified_at = now();
        $user->password = Hash::make('AAAbbb111222@');
        $user->remember_token = Str::random(10);
        $user->save();

        $employee = new Employee();
        $employee->user_id = $user->id;
        $employee->company_id = $company->id;
        $employee->name = 'Ricardo';
        $employee->id_legal = '00000000';
        $employee->employee_code = 'abcdefg';
        $employee->contact = [
            'email' => 'email@ejemplo.com',
            'phone_number' => '+54111222333',
        ];
        $employee->admission_date = '2022-02-22';
        $employee->finish_date = '2022-02-23';
        $employee->state = 'inactive';
        $employee->role = [];

        $employee->save();

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

        $credentials = [
            'key_id' => $keyId,
            'email' => CustomEncrypter::encryptOpenSSL('employee-update-company@email.com', $sharedKey),
            'password' => CustomEncrypter::encryptOpenSSL('AAAbbb111222@', $sharedKey)
        ];

        $response = $this->postJson(
            '/api/session/login',
            $credentials
        );
        $response->assertStatus(200);

        $token = $response['session'];
        $company_id = $response['user']['id'];

        $encryptionKeyResponse = $this->postJson('/api/encryptkey', [
            'publicKey' => $publicPem
        ]);

        $keyId = $encryptionKeyResponse["key_id"];
        $encryptedKey = $encryptionKeyResponse["encryptedKey"];
        openssl_private_decrypt(base64_decode($encryptedKey), $sharedKey, $privatePem, OPENSSL_PKCS1_PADDING);

        $data = [
            'key_id' => $keyId,
            'company_id' => CustomEncrypter::encryptOpenSSL($company_id, $sharedKey),
            'employee' => [
                'employee_id' => CustomEncrypter::encryptOpenSSL($user->id, $sharedKey),
                'dni' => CustomEncrypter::encryptOpenSSL('11111111', $sharedKey),
                'address' => [
                    'country' => CustomEncrypter::encryptOpenSSL('arg', $sharedKey),
                    'province' => CustomEncrypter::encryptOpenSSL('bsas', $sharedKey),
                    'city' => CustomEncrypter::encryptOpenSSL('ciudad falsa', $sharedKey),
                    'address' => CustomEncrypter::encryptOpenSSL('direccion false', $sharedKey),
                    'zipcode' => CustomEncrypter::encryptOpenSSL('ab555', $sharedKey)
                ],
                'contact' => [
                    'email' => CustomEncrypter::encryptOpenSSL('test@gmail.com', $sharedKey),
                    'phone' => CustomEncrypter::encryptOpenSSL('0sadfs00', $sharedKey)
                ],
                'admission_date' => '2024-02-02',
                'role' => 'developer'
            ]
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->putJson(
                '/api/enterprise/employee/modify',
                $data
            );

        $response->assertStatus(200);/* 
        dd($response->json()); */
    }
}
