<?php

namespace Tests\Unit;

use App\Models\Absence;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AbsenceModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_absence(): void
    {
        $user_company = new User();
        $user_company->id = Str::uuid(36);
        $user_company->email = 'absence-company@mail.com';
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
        $user->email = 'absence-employee@mail.com';
        $user->email_verified_at = now();
        $user->password = Hash::make('password');
        $user->remember_token = Str::random(10);
        $user->save();
        $this->assertModelExists($user);

        $employee = new Employee();
        $employee->user_id = $user->id;
        $employee->company_id = $company->id;

        $employee->save();
        $this->assertModelExists($employee);

        $absence = new Absence();
        $absence->employee_id = $employee->id;
        $absence->date = '2024-02-20';
        $absence->save();

        $this->assertModelExists($absence);
    }
}
