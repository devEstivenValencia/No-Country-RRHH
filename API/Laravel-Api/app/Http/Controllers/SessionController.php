<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SessionRequest;
use Carbon\Carbon;


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
