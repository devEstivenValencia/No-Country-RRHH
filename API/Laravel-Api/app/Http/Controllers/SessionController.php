<?php

namespace App\Http\Controllers;

use App\Classes\UserCapabilities;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SessionRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class SessionController extends Controller
{
    public function store(SessionRequest $request)
    {
        $request->validated();

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            $user->tokens()->delete();

            if ($company = $user->company()->first()) {
                $token = $user->createToken('token', UserCapabilities::company(), Carbon::now()->addDays(7));

                return response()->json(
                    [
                        'session' => $token->plainTextToken,
                        'user' => array_merge(
                            $user->toArray(),
                            $company->toArray()
                        )
                    ],
                    200
                );
            } elseif ($employee = $user->employee()->first()) {
                $token = $user->createToken('token', UserCapabilities::employee(), Carbon::now()->addDays(7));

                return response()->json(
                    [
                        'session' => $token->plainTextToken,
                        'user' => array_merge(
                            $user->toArray(),
                            $employee->toArray()
                        )
                    ],
                    200
                );
            }
        }
        return response()->json([
            'error' => 'UNAUTHORIZED'
        ], 401);
    }

    public function validate_token(Request $request)
    {
        $tokenWithBearer = $request->header('Authorization');
        $token = substr($tokenWithBearer, 7);
        $data = $request->validate(
            [
                'session' => 'required'
            ]
        );
        if ($data['session'] === $token) {
            return response()->json(['success' => true], 200);
        }
        return response()->json([
            'error' => 'SESSION_NOT_FOUND'
        ], 404);
    }

    public function destroy(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $tokenWithBearer = $request->header('Authorization');
        $token = substr($tokenWithBearer, 7);
        $data = $request->validate(
            [
                'session' => 'required'
            ]
        );
        if ($data['session'] === $token) {
            $user->tokens()->delete();
            return response()->json([
                'success' => true
            ], 200);
        }
        return response()->json([
            'error' => 'INVALID_TOKEN'
        ], 404);
    }
}
