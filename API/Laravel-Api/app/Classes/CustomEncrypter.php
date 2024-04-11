<?php

namespace App\Classes;

use Illuminate\Encryption\Encrypter;

class CustomEncrypter
{
    static public function encrypt($toEncrypt)
    {
        $newEncrypter = new Encrypter(base64_decode(Env('SECOND_KEY')), 'AES-256-CBC');

        $data = [];

        foreach ($toEncrypt as $key => $value) {
            $data[$key] = $newEncrypter->encrypt($value);
        }

        return $data;
    }

    static public function decrypt($toDecrypt)
    {
        $newEncrypter = new Encrypter(base64_decode(Env('SECOND_KEY')), 'AES-256-CBC');

        $dataDecrypted = [];

        try {
            foreach ($toDecrypt as $key => $value) {
                $dataDecrypted[$key] = $newEncrypter->decrypt($value);
            }
            return $dataDecrypted;
        } catch (\Throwable $th) {
            return $dataDecrypted;
        }
    }
}
