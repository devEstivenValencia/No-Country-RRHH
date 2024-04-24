<?php

namespace App\Http\Controllers;

use App\Classes\CustomEncrypter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\KeyManager;
use Carbon\Carbon;

class EncryptController extends Controller
{
    public function encrypt_data(Request $request)
    {
        try {
            $data = CustomEncrypter::recurse(array(CustomEncrypter::class, 'encryptString'), $request->toArray());
            /* $data = CustomEncrypter::encrypt($request->toArray()); */
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'INVALID_INPUT'], 400);
        }
    }

    public function generate_key(Request $request)
    {
        $publicKey = $request->get('publicKey');

        $rand = random_bytes(32);
        $sharedKey = $rand;

        $keyManager = new KeyManager();
        $keyManager->id = Str::uuid();
        $keyManager->key = base64_encode($sharedKey);
        $keyManager->expires_at = Carbon::now()->addMinutes(6);
        $keyManager->save();

        openssl_public_encrypt($sharedKey, $encryptedKey, $publicKey, OPENSSL_PKCS1_PADDING);

        return response()->json([
            'encryptedKey' => base64_encode($encryptedKey),
            'key_id' => $keyManager->id, // uuid string
        ], 200);
    }
}
