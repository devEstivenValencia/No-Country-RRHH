<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeCreateTest extends TestCase
{

    /** @test */
    public function a_employee_can_be_created(): void
    {


        $encryptionResponse = $this->postJson('/api/encrypt', [
            'credentials' =>
            [
                'email' => 'testCreated@example.com',
                'password' => '12Jhasass85@'
            ],
            'contact' => [
                'email' => 'testCreated@example.com',
                'phone' => '123456789'
            ]

        ]);



        // Extraes los datos encriptados de la respuesta
        $data = $encryptionResponse->json();
        // Enviamos una solicitud para crear una compañía
        $arrayMerge = array_merge($data, ['company_name' => 'Test Employee']);
        $response = $this->postJson('/api/account/enterprise/register', $arrayMerge);
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
                        'phone_number',
                        'email'
                    ]
                ]
            ]);

        $token = $response['session'];
        $encryptionResponseCreated = $this->postJson('/api/encrypt', [
            'company_id' => $company_id,
            'employee' => [
                'dni' => "123654874",
                'address' => [
                    'country' => "Argentina",
                    'province' => "San Luis",
                    'city' => "Capital",
                    'address' => "Esteban Roque 1236",
                    'zipcode' => "5700",
                ],
                'contact' => [
                    'email' => "employeeTest@mail.com",
                    'phone' => "12577895"
                ],
                'credentials' => [
                    'email' => "EmployeeMail@test.com", 'password' => "125Rt@fdafaf"
                ]
            ]
        ]);

        $dataCreated = $encryptionResponseCreated->json();
        // $arrayMergeCreate = array_merge($dataUpdate, [
        //     'name' => 'Employee created',
        //     "admission_date" => "22-04-2024", "role" => "Supervisor"
        // ]);

        $responseUpdate = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->postJson('/api/enterprise/employee/create', [
            'company_id' => $dataCreated['company_id'],
            'employee' => array_merge($dataCreated['employee'], [
                'name' => 'Employee created',
                "admission_date" => "2024-04-22", "role" => "Supervisor"
            ])
        ]);

        $responseUpdate->assertStatus(200)
            ->assertJson([
                'success' => true,

            ]);
    }
}
