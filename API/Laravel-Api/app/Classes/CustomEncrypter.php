<?php

namespace App\Classes;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Crypt;

class CustomEncrypter
{
    //return public and private key for feature tests
    static public function getKeyPairForTests()
    {
        return [
            'publicPem' => "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnWDNjOExqpss+zD7FPZG
WnMNFXsyuL5FM9jNvZLfq0EYS/9Hjlelv+Ihqwt01vcYZAqHURfEHiNFO+40711/
kIl43ZOIEKOb9c9FDjFtOeFiHx4gHZLSfwLVXysmHEzzY6en/uIzaOsOkBFl7hOc
7FXa9OP5IN8CD8sUuBh6sbujTwcyYmRHr3fuwRgNVG02dLYRPi2j6f3kHWGgbADw
wmd2bTO6nLGt1PuNQZMeDuSmBJNJHJnJvKPUGxgO2QU0vDlU3piLCRCd+OE65PQH
A0qy57AF/J2wmK9xV0Q6rQMgCHxkrecL41HC5ucHIQxfignL6yLU0fU07XZYkKHV
rwIDAQAB
-----END PUBLIC KEY-----
",
            'privatePem' => "-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAnWDNjOExqpss+zD7FPZGWnMNFXsyuL5FM9jNvZLfq0EYS/9H
jlelv+Ihqwt01vcYZAqHURfEHiNFO+40711/kIl43ZOIEKOb9c9FDjFtOeFiHx4g
HZLSfwLVXysmHEzzY6en/uIzaOsOkBFl7hOc7FXa9OP5IN8CD8sUuBh6sbujTwcy
YmRHr3fuwRgNVG02dLYRPi2j6f3kHWGgbADwwmd2bTO6nLGt1PuNQZMeDuSmBJNJ
HJnJvKPUGxgO2QU0vDlU3piLCRCd+OE65PQHA0qy57AF/J2wmK9xV0Q6rQMgCHxk
recL41HC5ucHIQxfignL6yLU0fU07XZYkKHVrwIDAQABAoIBACbGWf6S4VzKfMuq
5sHkZ7MTU67uOrH/1ycODKrfjBIyqJzY2bV9bM4ZFfiBixkHqk/DtFFLjLtsLzuz
1gD/xVVEOa5GEikfVma62aXEkcfH+xf1oS4IJaq7dPzMscgZQYJVJXQMIWSBPYZL
cxS0al5kTN03agMqTuS7QXCWP2yYjlkvh+vPn3Bz/BRzW/WI3E6C3mUM/JVNcmLE
PLXOeIHzHNQg/HdU+bQvXRSV40K6iGhXLY6tmREUSrPPBbveL73XjE9i1Q9WeAZf
ogki/RmCPwdiGVRRBoCeOapr150XRs5H070OAdyklL2eL+8WKhBqaeeuGEkfz+iO
3Kl5qskCgYEA1+O0BmDetoXgUNwByviM4/f1f9VD3/wauoyhmxR2ZHOfHx/1WmR8
wroN131FMDEQFcsImAYKqRxExn0s0tmyYnkmpnqB2pqPX2guwOVOiU6OQmvafd2G
fbtOoeljUeoheyhZI9UpeO39OR6VZZhqSTkPMZ+sNZL4tSSmLVRxwVsCgYEAup4j
1EM6tvlPH06TIyjDxWTHFevxdeO9dzdy1ZqWGdUvkndfHxnKhPQBTUF9oyavLTt3
elhqYZIo/kzL7eaPcEwvQ6D/rW6365YLppWs+HVQMXwFt7WnVVZ2SdSPHbtOBqPx
0bWoreqO4EfWIf9A9gsmGhhZVvJgaaVZw+QkuT0CgYB+po+jOksNiuZ4qUjH+R42
Uu4MC2HhUfWgQQZBgzSdY5MS4F3ApLjoR9v88fmX9wB3uH+XJ9wzL2lNhHu6Sooy
6N36xl3Nj+TSVL0w3pU/Sf19C+bG9iGDO1I6/D8O/CqeJKJ+ufu1fPCUBNQiINMh
l77JHuIZH9qV8iUm7KY7hwKBgEsm1Bbu2/5Dd/GLmwVVrUEYzHOviJWJGQuhcMVE
c54DlBzPpabIpB3WhY+vCAFj2rfEcuD6Kh0Q1yFRm+46Ixl+FKqPc2dMZxSgYCC9
teJ4m8rRvxu0M6bSIuCokNnQTCOwcfmZxkwDYXZAd6NJP8fVxYpMs+2nvdvNktCo
V4GhAoGBALUJzib1FfCagkAuBIObgQfqszFUuqfg9f0IfFiccs/pBrjO93Q7FTyT
z/h4PrC+lJgimMCJRnFemh2asmNJxxmGMmEEsSxNyPlNFKcQv2s3PdERuk7NlrLO
CT0Nh/wSrIO8P5hfSL5anR+KCvFrDlEfeol+P7bIiM4y9D9/BJBU
-----END RSA PRIVATE KEY-----
"
        ];
    }

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
