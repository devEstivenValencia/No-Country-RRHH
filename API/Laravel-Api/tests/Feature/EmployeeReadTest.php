<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class EmployeeReadTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
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


        $credentialsToEncrypt = [
            'email' => 'employee-read-company@email.com',
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
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('/api/enterprise/employee');
        $response->assertStatus(200)->assertJsonStructure([
            'company_id',
            'employees' => [
                '*' => [
                    'employee_id',
                    'name',
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
            ]
        ]);
    }
}
