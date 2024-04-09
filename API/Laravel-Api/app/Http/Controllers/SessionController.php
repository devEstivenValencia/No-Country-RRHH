<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\SessionRequest;

class SessionController extends Controller
{
    public function store(SessionRequest $request)
    {
        $data = $request->validated();
        $user = User::create(['email' => $data['email'], 'password' => Hash::make($data['password']), 'disabled' => false]);


        $company = new Company();
        $company->id = Str::uuid();
        $company->user_id = $user->id;
        $company->save();

        return response()->json(
            ['token' => $user->createToken('token')->plainTextToken, 'user' => $user],
            201
        );
    }

    public function validate_token()
    {
        //
    }

    public function destroy()
    {
        //
    }
}
