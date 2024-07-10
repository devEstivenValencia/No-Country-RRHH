<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeesModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_employee_with_data(): void
    {

        $user_company = new User();
        $user_company->id = Str::uuid(36);
        $user_company->email = 'employee-company@mail.com';
        $user_company->email_verified_at = now();
        $user_company->password = Hash::make('password');
        $user_company->remember_token = Str::random(10);
        $user_company->save();
        $this->assertModelExists($user_company);

        $company = new Company();
        $company->id = Str::uuid();
        $company->user_id = $user_company->id;
        $company->save();
        $this->assertModelExists($company);

        $user = new User();
        $user->id = Str::uuid(36);
        $user->email = 'employee-employee@mail.com';
        $user->email_verified_at = now();
        $user->password = Hash::make('password');
        $user->remember_token = Str::random(10);
        $user->save();
        $this->assertModelExists($user);

        $employee = new Employee();
        $employee->user_id = $user->id;
        $employee->company_id = $company->id;
        $employee->name = 'Ricardo';
        $employee->id_legal = '00000000';
        $employee->employee_code = 'abcdefg';
        $employee->address = 'calle falsa 123';
        $employee->contact = [
            'email' => 'email@ejemplo.com',
            'phone_number' => '+54111222333',
        ];
        $employee->admission_date = '2022-02-22';
        $employee->finish_date = '2022-02-23';
        $employee->state = 'inactive';
        $employee->role = [];

        $employee->save();
        $this->assertModelExists($employee);
        $this->assertEquals($employee->name, 'Ricardo');
        $this->assertEquals($employee->id_legal, '00000000');
        $this->assertEquals($employee->address, 'calle falsa 123');
        $this->assertEquals($employee->contact, [
            'email' => 'email@ejemplo.com',
            'phone_number' => '+54111222333',
        ]);
    }
}
