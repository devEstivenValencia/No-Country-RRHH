<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Performance;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class PerformanceModelTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_create_performance(): void
    {

        $user_company = new User();
        $user_company->id = Str::uuid(36);
        $user_company->email = Crypt::encryptString('empresa@mail.com');
        $user_company->email_verified_at = now();
        $user_company->password = Hash::make('password');
        $user_company->remember_token = Str::random(10);
        $user_company->save();
        $this->assertModelExists($user_company);

        $company = new Company();
        $company->id = Str::uuid();
        $company->user_id = $user_company->id;
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

        $user = new User();
        $user->id = Str::uuid(36);
        $user->email = Crypt::encryptString('hola@mail.com');
        $user->email_verified_at = now();
        $user->password = Hash::make('password');
        $user->remember_token = Str::random(10);
        $user->save();
        $this->assertModelExists($user);

        $employee = new Employee();
        $employee->user_id = $user->id;
        $employee->company_id = $company->id;
        $employee->name = Crypt::encryptString('Ricardo');
        $employee->id_legal = Crypt::encryptString('00000000');
        $employee->employee_code = 'abcdefg';
        $employee->address = Crypt::encryptString('calle falsa 123');
        $employee->contact = Crypt::encrypt([
            'email' => 'email@ejemplo.com',
            'phone_number' => '+54111222333',
        ]);
        $employee->admission_date = '2022-02-22';
        $employee->finish_date = '2022-02-23';
        $employee->state = 'inactive';
        $employee->role = [];

        $employee->save();
        $this->assertModelExists($employee);
        $this->assertEquals(Crypt::decryptString($employee->name), 'Ricardo');
        $this->assertEquals(Crypt::decryptString($employee->id_legal), '00000000');
        $this->assertEquals(Crypt::decryptString($employee->address), 'calle falsa 123');
        $this->assertEquals(Crypt::decrypt($employee->contact), [
            'email' => 'email@ejemplo.com',
            'phone_number' => '+54111222333',
        ]);

        $performace = new Performance();
        $performace->id = Str::uuid(36);
        $performace->employee_id = $employee->id;
        $performace->rating = 9.6;
        $performace->save();
    }
}
