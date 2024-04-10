<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\SessionRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function store(SessionRequest $request)
    {
        $request->validated();

        if (Auth::attempt($request->only(['email', 'password']))) {
            $user = User::find(Auth::user()->id);

            $user->tokens()->delete();
            $token = $user->createToken('token', ['*'], Carbon::now()->addDays(7));

            return response()->json(
                [
                    'session' => $token->plainTextToken,
                    'user' => $user
                ],
                200
            );
        }
        return response()->json('error');
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
