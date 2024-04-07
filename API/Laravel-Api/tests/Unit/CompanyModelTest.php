<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\User;/* 
use PHPUnit\Framework\TestCase; */
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Support\Str;

class CompanyModelTest extends TestCase
{
    /* 
    use RefreshDatabase; */
    /**
     * A basic unit test example.
     */
    public function test_create_user_company(): void
    {
        $user = new User();
        $user->id = Str::uuid(36);
        $user->email = Crypt::encryptString('hola@mail.com');
        $user->email_verified_at = now();
        $user->password = Hash::make('password');
        $user->remember_token = Str::random(10);
        $user->save();

        $user->createToken("auth_token")->plainTextToken;

        $this->assertModelExists($user);
        $this->assertEquals(Crypt::decryptString($user->email), 'hola@mail.com');

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
        $user->email = Crypt::encryptString('empresa@mail.com');
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
        $company->location = Crypt::encrypt([
            'country' => 'argentina',
            'province' => 'buenos aires',
            'city' => 'Mar del plata',
            'address' => 'Av. independencia 1500',
            'zipcode' => 'ABC7600',
        ]);
        $company->contact = Crypt::encrypt([
            'email' => 'email@ejemplo.com',
            'phone_number' => '+54111222333',
        ]);
        $company->save();
        $this->assertModelExists($company);
        $this->assertEquals(Crypt::decrypt($company->location), [
            'country' => 'argentina',
            'province' => 'buenos aires',
            'city' => 'Mar del plata',
            'address' => 'Av. independencia 1500',
            'zipcode' => 'ABC7600',
        ]);
        $this->assertEquals(Crypt::decrypt($company->contact), [
            'email' => 'email@ejemplo.com',
            'phone_number' => '+54111222333',
        ]);
    }
}
