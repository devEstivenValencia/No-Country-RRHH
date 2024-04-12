<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public function store(CompanyRequest $request)
    {
        $data = $request->validated();
        $user = User::create(['email' => $data['email'], 'password' => Hash::make($data['password']), 'disabled' => false]);

        $company = new Company();
        $company->id = Str::uuid();
        $company->user_id = $user->id;
        $company->company_name = $data['company_name'];
        $company->contact = Crypt::encrypt([
            'phone_number' => $data['phone_number']
        ]);
        $company->save();

        return response()->json(
            ['token' => $user->createToken('token')->plainTextToken,],
            200
        );
    }
}
