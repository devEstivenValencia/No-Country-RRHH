<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\NonWorkingDays;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class NonWorkingDaysModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_non_working_day(): void
    {
        $user = new User();
        $user->id = Str::uuid(36);
        $user->email = 'nonworkingdays-company@mail.com';
        $user->email_verified_at = now();
        $user->password = Hash::make('password');
        $user->remember_token = Str::random(10);
        $user->save();
        $this->assertModelExists($user);

        $company = new Company();
        $company->id = Str::uuid();
        $company->user_id = $user->id;
        $company->save();
        $this->assertModelExists($company);

        $non_working_day = new NonWorkingDays();
        $non_working_day->company_id = $company->id;
        $non_working_day->name = 'navidad';
        $non_working_day->description = 'feriado obligatorio';
        $non_working_day->date = '2024-12-24';
        $non_working_day->save();
        $this->assertModelExists($non_working_day);
    }
}
