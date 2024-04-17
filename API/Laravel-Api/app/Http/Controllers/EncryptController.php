<?php

namespace App\Http\Controllers;

use App\Classes\CustomEncrypter;
use Illuminate\Http\Request;

class EncryptController extends Controller
{
    public function encrypt_data(Request $request)
    {
        try {
            $data = CustomEncrypter::encrypt($request->toArray());
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'INVALID_INPUT'], 400);
        }
    }
}
