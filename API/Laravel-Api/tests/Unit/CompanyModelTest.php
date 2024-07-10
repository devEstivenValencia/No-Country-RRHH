<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\User;/* 
use PHPUnit\Framework\TestCase; */
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Support\Str;

class CompanyModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user_company(): void
    {
        $user = new User();
        $user->id = Str::uuid(36);
        $user->email = 'company-company@mail.com';
        $user->email_verified_at = now();
        $user->password = Hash::make('password');
        $user->remember_token = Str::random(10);
        $user->save();

        $user->createToken("auth_token")->plainTextToken;

        $this->assertModelExists($user);
        $this->assertEquals($user->email, 'company-company@mail.com');

        $company = new Company();
        $company->id = Str::uuid();
        $company->user_id = $user->id;
        $company->save();
        $this->assertModelExists($company);
    }


    public function test_create_company_with_data(): void
    {
        $user = new User();
        $user->id = Str::uuid(36);
        $user->email = 'company-data-company@mail.com';
        $user->email_verified_at = now();
        $user->password = Hash::make('password');
        $user->remember_token = Str::random(10);
        $user->save();

        $this->assertModelExists($user);

        $company = new Company();
        $company->id = Str::uuid();
        $company->user_id = $user->id;
        $company->company_name = "empresa";
        $company->working_week = ['lu', 'ma', 'mi'];
        $company->roles = ['rrhh', 'empleado base'];
        $company->sector = "ventas";
        $company->location = [
            'country' => 'argentina',
            'province' => 'buenos aires',
            'city' => 'Mar del plata',
            'address' => 'Av. independencia 1500',
            'zipcode' => 'ABC7600',
        ];
        $company->contact = [
            'email' => 'email@ejemplo.com',
            'phone_number' => '+54111222333',
        ];
        $company->save();
        $this->assertModelExists($company);
        $this->assertEquals($company->location, [
            'country' => 'argentina',
            'province' => 'buenos aires',
            'city' => 'Mar del plata',
            'address' => 'Av. independencia 1500',
            'zipcode' => 'ABC7600',
        ]);
        $this->assertEquals($company->contact, [
            'email' => 'email@ejemplo.com',
            'phone_number' => '+54111222333',
        ]);
    }
}
