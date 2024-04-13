<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public function store(CompanyRequest $request)
    {
        $data = $request->validated();
        $user = User::create(['email' => $data['credentials']['email'], 'password' => Hash::make($data['credentials']['password']), 'disabled' => false]);

        $company = new Company();
        $company->id = Str::uuid();
        $company->user_id = $user->id;
        $company->company_name = $data['company_name'];
        $company->contact = Crypt::encrypt([
            'phone_number' => $data['contact']['phone'],
            'email' => $data['contact']['email']
        ]);
        $company->save();

        return response()->json(
            [
                'session' => $user->createToken('token')->plainTextToken,
                'user' => array_merge(
                    $user->toArray(),
                    $company->toArray(),
                    ['contact' => Crypt::decrypt($company->contact)]
                )
            ],
            201
        );
    }
    public function update(CompanyUpdateRequest $request)
    {
        $data = $request->validated();
        $company = Company::where('user_id', '=', Auth::user()->id)->first();
        $company->company_name = $data['company_name'];
        $company->contact = Crypt::encrypt([
            'phone_number' => $data['contact']['phone'],
            'email' => $data['contact']['email']
        ]);
        $company->save();

        return response()->json([
            'success' => true
        ], 200);
    }
}
