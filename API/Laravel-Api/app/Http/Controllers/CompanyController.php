<?php

namespace App\Http\Controllers;

use App\Classes\CustomEncrypter;
use App\Classes\UserCapabilities;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Models\User;
use App\Models\Company;
use App\Models\KeyManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
        $company->verified = false;
        $company->contact = [
            'phone_number' => $data['contact']['phone'],
            'email' => $data['contact']['email']
        ];

        $company->save();

        /* return response()->json(
            [
                'session' => $user->createToken('token', UserCapabilities::company(), Carbon::now()->addDays(7))->plainTextToken,
                'user' => array_merge(
                    $user->toArray(),
                    $company->toArray()
                )
            ],
            201
        ); */


        //new
        $sharedKey = base64_decode($request->shared_key);
        KeyManager::find($request->key_id)->delete();
        return response()->json(
            [
                'session' => $user->createToken('token', UserCapabilities::company(), Carbon::now()->addDays(7))->plainTextToken,
                'user' => array_merge(
                    $user->toArray(),
                    $company->toArray(),
                    [
                        'contact' => [
                            'email' => CustomEncrypter::encryptOpenSSL($company->contact['email'], $sharedKey),
                            'phone' => CustomEncrypter::encryptOpenSSL($company->contact['phone_number'], $sharedKey),
                        ],
                        'type' => 'company',
                        'verified' => false
                    ]
                )
            ],
            201
        );
        //end
    }
    public function update(CompanyUpdateRequest $request)
    {
        $data = $request->validated();

        $user = User::find(Auth::user()->id);
        $company = $user->company;

        if ($company) {
            $sharedKey = base64_decode($request->shared_key);
            KeyManager::find($request->key_id)->delete();

            $company->contact = [
                'phone_number' => $data['contact']['phone'],
                'email' => $data['contact']['email']
            ];
            $company->location = [
                'country' => $data['location']['country'],
                'province' => $data['location']['province'],
                'city' => $data['location']['city'],
                'address' => $data['location']['address']
            ];
            $company->sector = $data['sector'];
            $company->verified = true;
            $company->save();

            return response()->json([
                'success' => true,
                'message' => 'se completo el registro',
                'user' => array_merge(
                    $user->toArray(),
                    $company->toArray(),
                    [
                        'contact' => [
                            'email' => CustomEncrypter::encryptOpenSSL($company->contact['email'], $sharedKey),
                            'phone' => CustomEncrypter::encryptOpenSSL($company->contact['phone_number'], $sharedKey),
                        ],
                        'location' => [
                            'country' => CustomEncrypter::encryptOpenSSL($company->location['country'], $sharedKey),
                            'province' => CustomEncrypter::encryptOpenSSL($company->location['province'], $sharedKey),
                            'city' => CustomEncrypter::encryptOpenSSL($company->location['city'], $sharedKey),
                            'address' => CustomEncrypter::encryptOpenSSL($company->location['address'], $sharedKey),
                        ],
                        'type' => 'company'
                    ]
                )
            ], 200);
        }

        return response()->json([
            'error' => 'UNAUTHORIZED',
            'message' => 'usuario no encontrado'
        ], 401);
    }
}
