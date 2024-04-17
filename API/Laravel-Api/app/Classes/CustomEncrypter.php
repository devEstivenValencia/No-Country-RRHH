<?php

namespace App\Classes;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Crypt;

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

        foreach ($toDecrypt as $key => $value) {
            try {
                $dataDecrypted[$key] = $newEncrypter->decrypt($value);
            } catch (DecryptException $e) {
                $dataDecrypted[$key] = '';
            }
        }
        return $dataDecrypted;
    }

    static public function decryptDB($value)
    {
        try {
            return Crypt::decrypt($value);
        } catch (DecryptException $e) {
            return null;
        }
    }

    static public function encryptDB($value)
    {
        try {
            return Crypt::encrypt($value);
        } catch (DecryptException $e) {
            return null;
        }
    }

    static public function recurse($callback, $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result[$key] = self::recurse($callback, $value);
            } elseif (is_string($value)) {
                $result[$key] = $callback($value);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }


    static public function encryptString($toEncrypt)
    {
        $newEncrypter = new Encrypter(base64_decode(Env('SECOND_KEY')), 'AES-256-CBC');

        return $newEncrypter->encryptString($toEncrypt);
    }

    static public function decryptString($toEncrypt)
    {
        $newEncrypter = new Encrypter(base64_decode(Env('SECOND_KEY')), 'AES-256-CBC');

        $dataDecrypted = '';

        try {
            $dataDecrypted = $newEncrypter->decryptString($toEncrypt);
        } catch (DecryptException $e) {
            $dataDecrypted = '';
        }

        return $dataDecrypted;
    }
}
