<?php

namespace App\Http\Requests;

use App\Classes\CustomEncrypter;
use App\Models\KeyManager;
use Illuminate\Http\Exceptions\HttpResponseException;

class SessionRequest extends CustomFormRequest
{
    protected function prepareForValidation()
    {/* 
        $dataDecrypted = CustomEncrypter::decrypt($this->toArray()); *//* 
        $dataDecrypted = CustomEncrypter::recurse(array(CustomEncrypter::class, 'decryptString'), $this->toArray()); */

        $keyToDecrypt = [
            'email',
            'password'
        ];

        $keyManager = KeyManager::find($this->key_id);
        if ($keyManager) {
            $sharedKey = $keyManager->key;

            $dataDecrypted = CustomEncrypter::recurseSpecificSchemaOpenSSL(
                $this->toArray(),
                $keyToDecrypt,
                base64_decode($sharedKey)
            );
            $dataDecrypted['shared_key'] = $sharedKey;
            //end new
            $this->merge($dataDecrypted);
        } else {
            throw new HttpResponseException(
                response()->json([
                    'error' => 'BAD_REQUEST',
                    'message' => 'error con la clave de encriptación'
                ], 400)
            );
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'
            ],
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email ingresado no es un email válido',
            'password.required' => 'El password es obligatorio',
            'password.min' => 'La contraseña debe tener al menos :min caracteres',
            'password.regex' => 'La contraseña debe contener al menos una letra minúscula, una letra mayúscula, un dígito, un carácter especial y tener una longitud mínima de 8 caracteres.',
        ];
    }
}
