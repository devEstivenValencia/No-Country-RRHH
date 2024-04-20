<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $user_company = new User();
        $user_company->id = Str::uuid(36);
        $user_company->email = 'employee-company@mail.com';
        $user_company->email_verified_at = now();
        $user_company->password = Hash::make('AAAbbb111222@');
        $user_company->remember_token = Str::random(10);
        $user_company->save();

        $company = new Company();
        $company->id = Str::uuid();
        $company->user_id = $user_company->id;
        $company->save();

        $user = new User();
        $user->id = Str::uuid(36);
        $user->email = 'employee-employee@mail.com';
        $user->email_verified_at = now();
        $user->password = Hash::make('AAAbbb111222@');
        $user->remember_token = Str::random(10);
        $user->save();

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
    }
}
