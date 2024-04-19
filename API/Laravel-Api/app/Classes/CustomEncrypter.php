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

    static public function recurseSpecificSchema($callback, $array, $schema): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value) && isset($schema[$key])) {
                $result[$key] = self::recurseSpecificSchema($callback, $value, $schema[$key]);
            } elseif (is_string($value) && in_array($key, $schema, true)) {
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


    //test new encrypt
    static public function recurseSpecificSchemaOpenSSL($array, $schema, $sharedKey): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value) && isset($schema[$key])) {
                $result[$key] = self::recurseSpecificSchemaOpenSSL($value, $schema[$key], $sharedKey);
            } elseif (is_string($value) && in_array($key, $schema, true)) {
                $result[$key] = self::decryptOpenSSL($value, $sharedKey);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
    static public function decryptOpenSSL($toDecrypt, $sharedKey)
    {
        // get the encrypted data from client and base 64 decode it
        $encryptedMsg = base64_decode($toDecrypt);

        // get the first 16 bytes from the payload (must match the IV byte length)
        $iv = mb_substr($encryptedMsg, 0, 16, '8bit');

        // get the encrypted value part (should match the rest of the payload)
        $encrypted = mb_substr($encryptedMsg, 16, null, '8bit');

        $dataDecrypted = '';

        try {
            $dataDecrypted = openssl_decrypt(
                $encrypted,
                'aes-256-cbc',
                $sharedKey,
                OPENSSL_RAW_DATA,
                $iv
            );
        } catch (\Exception $e) {
            $dataDecrypted = '';
        }

        return $dataDecrypted;
    }

    static public function encryptOpenSSL($toEncrypt, $sharedKey)
    {
        $iv = openssl_random_pseudo_bytes(16);

        $dataDecrypted = '';

        try {
            $dataDecrypted = base64_encode($iv . openssl_encrypt(
                $toEncrypt,
                'aes-256-cbc',
                $sharedKey,
                OPENSSL_RAW_DATA,
                $iv
            ));
        } catch (\Exception $e) {
            $dataDecrypted = '';
        }

        return $dataDecrypted;
    }
}
