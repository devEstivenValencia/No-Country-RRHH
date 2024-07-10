<?php

namespace App\Http\Controllers;

use App\Classes\CustomEncrypter;
use App\Classes\UserCapabilities;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SessionRequest;
use App\Models\KeyManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class SessionController extends Controller
{
    public function store(SessionRequest $request)
    {
        $request->validated();

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $sharedKey = base64_decode($request->shared_key);
            KeyManager::find($request->key_id)->delete();

            $user->tokens()->delete();

            if ($company = $user->company()->first()) {
                $token = $user->createToken('token', UserCapabilities::company(), Carbon::now()->addDays(7));

                return response()->json(
                    [
                        'session' => $token->plainTextToken,
                        'user' => array_merge(
                            $user->toArray(),
                            $company->toArray(),
                            [
                                'type' => 'company',
                                'verified' => $company->verified,
                                'contact' => [
                                    'email' => CustomEncrypter::encryptOpenSSL(isset($company->contact['email']) ? $company->contact['email'] : '', $sharedKey),
                                    'phone' => CustomEncrypter::encryptOpenSSL(isset($company->contact['email']) ? $company->contact['phone_number'] : '', $sharedKey),
                                ]
                            ]
                        )
                    ],
                    200
                );
            } elseif ($employee = $user->employee()->first()) {
                $token = $user->createToken('token', UserCapabilities::employee(), Carbon::now()->addDays(7));

                $contact = null;
                if (isset($company->contact)) {
                    $contact = [
                        'email' => CustomEncrypter::encryptOpenSSL($company->contact['email'], $sharedKey),
                        'phone' => CustomEncrypter::encryptOpenSSL($company->contact['phone_number'], $sharedKey),
                    ];
                }
                return response()->json(
                    [
                        'session' => $token->plainTextToken,
                        'user' => array_merge(
                            $user->toArray(),
                            $employee->toArray(),
                            [
                                'type' => 'employee',
                                'contact' => $contact
                            ]
                        )
                    ],
                    200
                );
            }
        }
        return response()->json([
            'error' => 'UNAUTHORIZED',
            'message' => 'el usuario o contraseña no son válidos'
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
            'error' => 'SESSION_NOT_FOUND',
            'message' => 'sesión no encontrada'
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
            'error' => 'INVALID_TOKEN',
            'message' => 'sesion invalida'
        ], 404);
    }
}
