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
    // use RefreshDatabase;

    /** @test */
    public function a_company_can_be_created()
    {
        $encryptionResponse = $this->postJson('/api/encrypt', [
            'credentials' =>
            [
                'email' => 'test12@example.com',
                'password' => '12Jhasass85@'
            ],
            'contact' => [
                'email' => 'test12@example.com',
                'phone' => '123456789'
            ]

        ]);



        // Extraes los datos encriptados de la respuesta
        $data = $encryptionResponse->json();
        // Enviamos una solicitud para crear una compañía
        $arrayMerge = array_merge($data, ['company_name' => 'Test Comany']);
        $response = $this->postJson('/api/account/enterprise/register', $arrayMerge);


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
        $encryptionResponseUpdate = $this->postJson('/api/encrypt', [

            'contact' => [
                'email' => 'testUpdate@example.com',
                'phone' => '989844566'
            ]

        ]);

        $dataUpdate = $encryptionResponseUpdate->json();
        $arrayMergeUpdate = array_merge($dataUpdate, ['company_name' => 'Test Comany update']);

        $responseUpdate = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->putJson('/api/account/enterprise/modify', $arrayMergeUpdate);

        $responseUpdate->assertStatus(200)
            ->assertJson([
                'success' => true,

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
