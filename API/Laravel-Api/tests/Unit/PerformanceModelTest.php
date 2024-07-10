<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Performance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PerformanceModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_performance(): void
    {

        $user_company = new User();
        $user_company->id = Str::uuid(36);
        $user_company->email = 'performance-company@mail.com';
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
        $user->email = 'performace-employee@mail.com';
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

        $performace = new Performance();
        $performace->id = Str::uuid(36);
        $performace->employee_id = $employee->id;
        $performace->rating = 9.6;
        $performace->save();
    }
}
