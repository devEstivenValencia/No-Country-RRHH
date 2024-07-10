<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Vacation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class VacationModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_vacation(): void
    {

        $user_company = new User();
        $user_company->id = Str::uuid(36);
        $user_company->email = 'vacation-company@mail.com';
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
        $user->email = 'vacation-employee@mail.com';
        $user->email_verified_at = now();
        $user->password = Hash::make('password');
        $user->remember_token = Str::random(10);
        $user->save();
        $this->assertModelExists($user);

        $employee = new Employee();
        $employee->id = Str::uuid();
        $employee->user_id = $user->id;
        $employee->company_id = $company->id;

        $employee->save();
        $this->assertModelExists($employee);

        $vacation = new Vacation();
        $vacation->id = Str::uuid(36);
        $vacation->employee_id = $employee->id;
        $vacation->initial_date = '2024-01-15';
        $vacation->final_date = '2024-02-15';
        $vacation->state = 'approved';
        $vacation->created_at = now();
        $vacation->save();
    }
}
