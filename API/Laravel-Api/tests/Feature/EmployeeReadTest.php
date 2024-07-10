<?php

namespace Tests\Feature;

use App\Classes\CustomEncrypter;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class EmployeeReadTest extends TestCase
{
    use RefreshDatabase;

    public function test_read_employee(): void
    {
        $user_company = new User();
        $user_company->id = Str::uuid(36);
        $user_company->email = 'employee-read-company@email.com';
        $user_company->email_verified_at = now();
        $user_company->password = Hash::make('AAAbbb111222@');
        $user_company->remember_token = Str::random(10);
        $user_company->save();

        $company = new Company();
        $company->id = Str::uuid();
        $company->user_id = $user_company->id;
        $company->company_name = "Compani Test";
        $company->save();

        $user = new User();
        $user->id = Str::uuid(36);
        $user->email = 'employee-read-employee@email.com';
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
        $employee->address = [
            "country" => "Argentina",
            "province" => "San Luis",
            "city" => "San luis",
            "address" => "Esteban Adaro 874",
            "zipcode" => "5700"
        ];
        $employee->contact = [
            'email' => 'email@ejemplo.com',
            'phone' => '+54111222333',
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
            'email' => CustomEncrypter::encryptOpenSSL('employee-read-company@email.com', $sharedKey),
            'password' => CustomEncrypter::encryptOpenSSL('AAAbbb111222@', $sharedKey)
        ];

        $response = $this->postJson(
            '/api/session/login',
            $credentials
        );
        $response->assertStatus(200);

        $token = $response['session'];

        $encryptionKeyResponse = $this->postJson('/api/encryptkey', [
            'publicKey' => $publicPem
        ]);

        $keyId = $encryptionKeyResponse["key_id"];
        $encryptedKey = $encryptionKeyResponse["encryptedKey"];
        openssl_private_decrypt(base64_decode($encryptedKey), $sharedKey, $privatePem, OPENSSL_PKCS1_PADDING);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('/api/enterprise/employee?key_id=' . $keyId);
        $response->assertStatus(200)->assertJsonStructure([
            'company_id',
            'employees' => [
                '*' => [
                    'id',
                    'name',
                    'dni',
                    'location' => [
                        'country',
                        'province',
                        'city',
                        'address'
                    ],
                    'contact' => [
                        'email',
                        'phone'
                    ]
                ]
            ]
        ]);
    }
}
