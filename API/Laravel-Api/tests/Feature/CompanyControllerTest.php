<?php

namespace Tests\Feature;

use App\Classes\CustomEncrypter;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_company_can_be_created()
    {
        $encryptionResponse = $this->postJson('/api/encrypt', [
            'credentials' =>
            [
                'email' => 'test1@example.com',
                'password' => '12Jhasass85@'
            ],
            'contact' => [
                'email' => 'test1@example.com',
                'phone' => '123456789'
            ],
            'company_name' => 'Test Company'
        ]);



        // Extraes los datos encriptados de la respuesta
        $data = $encryptionResponse->json();
        // Enviamos una solicitud para crear una compañía

        $response = $this->postJson('/api/account/enterprise/register', [
            'credentials' => $data['credentials'],
            'contact' => $data['contact'],
            'company_name' => 'Test Company'
        ]);
        dd($response);
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
    }


    /** @test */
    public function a_company_can_be_updated()
    {
        $encryptionResponse = $this->postJson('/api/encrypt', [
            'credentials' =>
            [
                'email' => 'test@example.com',
                'password' => '12Jhasass85@'
            ],
            'contact' => [
                'email' => 'test@example.com',
                'phone' => '123456789'
            ],
            'company_name' => 'Test Company'
        ]);



        // Extraes los datos encriptados de la respuesta
        $data = $encryptionResponse->json();
        // Enviamos una solicitud para crear una compañía

        $response = $this->postJson('/api/account/enterprise/register', [
            'credentials' => $data['credentials'],
            'contact' => $data['contact'],
            'company_name' => 'Test Company'
        ]);



        // Enviamos una solicitud para actualizar la compañía
        $response = $this->putJson('/api/account/enterprise/modify', [
            'company_name' => 'Updated Company',
            'contact' => [
                'email' => 'updated@example.com',
                'phone_number' => '987654321'
            ]
        ]);

        // Verificamos el estado de la respuesta y el contenido
        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }
}
