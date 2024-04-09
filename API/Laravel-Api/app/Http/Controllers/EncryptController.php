<?php

namespace App\Http\Controllers;

use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Crypt;
use PSpell\Config;

class EncryptController extends Controller
{
    public function encrypt_data(Request $request)
    {
        $newEncrypter = new Encrypter(base64_decode(Env('SECOND_KEY')), 'AES-256-CBC');

        $data = $request->toArray();

        foreach ($data as $key => $value) {
            $data[$key] = $newEncrypter->encrypt($value);
        }

        return response()->json($data, 200);
    }
}
