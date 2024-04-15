<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_company()
    {
        // Simulate authentication
        $user = User::factory()->create();
        $this->actingAs($user);

        // Perform request to store a new company
        $response = $this->postJson('/api/companies', [
            // Provide necessary data for company creation
        ]);

        // Assert response status and content
        $response->assertStatus(201)
            ->assertJson([
                // Check if the response matches expected format
            ]);
    }

    /** @test */
    public function it_can_update_a_company()
    {
        // Simulate authentication
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a company to be updated
        $company = Company::factory()->create(['user_id' => $user->id]);

        // Perform request to update the company
        $response = $this->putJson('/api/companies', [
            // Provide necessary data for company update
        ]);

        // Assert response status and content
        $response->assertStatus(200)
            ->assertJson([
                // Check if the response matches expected format
            ]);
    }
}
