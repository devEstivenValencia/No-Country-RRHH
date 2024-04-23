<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;

class EmployeeUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
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


        $credentialsToEncrypt = [
            'email' => 'employee-update-company@email.com',
            'password' => 'AAAbbb111222@'
        ];

        $response = $this->postJson('/api/encrypt', $credentialsToEncrypt);
        $response->assertStatus(200)->assertJsonStructure([
            'email',
            'password'
        ]);

        $credentials = $response->json();

        $response = $this->postJson(
            '/api/session/login',
            $credentials
        );
        $response->assertStatus(200);

        $token = $response['session'];
        $company_id = $response['user']['id'];


        $requestToEncrypt = [
            'company_id' => $company_id,
            'employee' => [
                'employee_id' => $user->id,
                'dni' => '11111111',
                'address' => [
                    'country' => 'arg',
                    'province' => 'bsas',
                    'city' => 'ciudad falsa',
                    'address' => 'direccion false',
                    'zipcode' => 'ab555'
                ],
                'contact' => [
                    'email' => 'test@gmail.com',
                    'phone' => '0sadfs00'
                ]
            ]
        ];

        $response = $this->postJson('/api/encrypt', $requestToEncrypt);
        $response->assertStatus(200)->assertJsonStructure([
            'company_id',
            'employee' => [
                'employee_id',
                'dni',
                'address' => [
                    'country',
                    'province',
                    'city',
                    'address',
                    'zipcode'
                ],
                'contact' => [
                    'email',
                    'phone'
                ]
            ]
        ]);

        $data = $response->json();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->putJson(
                '/api/enterprise/employee/modify',
                [
                    'company_id' => $data['company_id'],
                    'employee' => array_merge(
                        $data['employee'],
                        [
                            'admission_date' => '2024-02-02',
                            'role' => 'developer'
                        ]
                    )
                ]
            );

        $response->assertStatus(200);/* 
        dd($response->json()); */
    }
}
