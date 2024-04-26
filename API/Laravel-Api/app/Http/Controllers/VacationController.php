<?php

namespace App\Http\Controllers;

use App\Http\Requests\VacationIndexRequest;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacationController extends Controller
{
    public function index(VacationIndexRequest $request): JsonResponse
    {
        $request->validated();
        $company = Company::where('user_id', Auth::user()->id)->first();


        if ($company) {/* 
            $key = KeyManager::find($request->query('key_id'));
            $sharedKey = base64_decode($key->key);
            $key->delete(); */

            $employees = $company->employees()->get();

            $employeesData = [];
            foreach ($employees as $employee) {
                $vacation = $employee->vacation;

                //if ($vacation) {
                $employeesData[] = [
                    'id' => $employee->user_id,
                    'name' => $employee->name,
                    'email' => $employee->contact['email'],
                    'role' => $employee->role,
                    "dates" => '20-20-2024 > 30-30-2024'/* $vacation->initial_date . ' > ' . $vacation->final_date */
                    /* 
                        'dni' => CustomEncrypter::encryptOpenSSL($employee->id_legal, $sharedKey),
                        "location" => [
                            'country' => CustomEncrypter::encryptOpenSSL($employee->address['country'], $sharedKey),
                            'province' => CustomEncrypter::encryptOpenSSL($employee->address['province'], $sharedKey),
                            'city' => CustomEncrypter::encryptOpenSSL($employee->address['city'], $sharedKey),
                            'address' => CustomEncrypter::encryptOpenSSL($employee->address['address'], $sharedKey),
                        ],
                        "contact" =>  [
                            'email' => CustomEncrypter::encryptOpenSSL($employee->contact['email'], $sharedKey),
                            'phone' => CustomEncrypter::encryptOpenSSL($employee->contact['phone'], $sharedKey),
                        ], */
                ];
                //}
            }

            $response = [
                'employees' => $employeesData,
            ];

            return response()->json($response);
        } else {
            return response()->json(['message' => 'No se encontró la compañía asociada al usuario.'], 404);
        }
    }
}
