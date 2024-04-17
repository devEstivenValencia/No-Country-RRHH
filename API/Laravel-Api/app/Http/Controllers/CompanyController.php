<?php

namespace App\Http\Controllers;

use App\Classes\UserCapabilities;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Models\User;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
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
        $company->contact = [
            'phone_number' => $data['contact']['phone'],
            'email' => $data['contact']['email']
        ];
        $company->save();

        return response()->json(
            [
                'session' => $user->createToken('token', UserCapabilities::company(), Carbon::now()->addDays(7))->plainTextToken,
                'user' => array_merge(
                    $user->toArray(),
                    $company->toArray()
                )
            ],
            201
        );
    }
    public function update(CompanyUpdateRequest $request)
    {
        $data = $request->validated();

        $user = Auth::user();
        $company = $user->company;

        $company->company_name = $data['company_name'];
        $company->contact = [
            'phone_number' => $data['contact']['phone'],
            'email' => $data['contact']['email']
        ];
        $company->save();

        return response()->json([
            'success' => true
        ], 200);
    }
}
