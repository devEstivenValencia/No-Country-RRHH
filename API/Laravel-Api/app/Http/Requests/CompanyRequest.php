<?php

namespace App\Http\Requests;

use App\Classes\CustomEncrypter;
use App\Models\KeyManager;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompanyRequest extends CustomFormRequest
{

    protected function prepareForValidation()
    {/*
        $allowed = ['credentials', 'contact'];
        $dataToDecrypt = array_intersect_key($this->toArray(), array_flip($allowed));

        $dataDecrypted = CustomEncrypter::decrypt($dataToDecrypt); */

        $keyToDecrypt = [
            'credentials' => [
                'email',
                'password'
            ],
            'contact' => [
                'email',
                'phone'
            ]
        ];

        /* $dataDecrypted = CustomEncrypter::recurseSpecificSchema(
            array(CustomEncrypter::class, 'decryptString'),
            $this->toArray(),
            $keyToDecrypt
        ); */

        //new
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
            'credentials.email' => 'required|email|unique:users,email',
            'credentials.password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'
            ],
            'contact.email' => 'required|email',
            'contact.phone' => 'required|string',
            'company_name' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'credentials.email.required' => 'El email es obligatorio',
            'credentials.email.email' => 'El email ingresado no es un email válido',
            'credentials.email.unique' => 'El email ya ha sido registrado',
            'credentials.email.exists' => 'El email no está registrado',

            'credentials.password.required' => 'El password es obligatorio',
            'credentials.password.min' => 'La contraseña debe tener al menos :min caracteres',
            'credentials.password.regex' => 'La contraseña debe contener al menos una letra minúscula, una letra mayúscula, un dígito, un carácter especial y tener una longitud mínima de 8 caracteres.',

            'contact.email.required' => 'El email de contacto es obligatorio',
            'contact.email.email' => 'El email de contacto ingresado no es un email válido',

            'contact.phone.required' => 'El número de contacto es obligatorio',
            'contact.phone.string' => 'El número de contacto debe ser texto',

            'company_name.required' => 'El nombre de la empresa es obligatorio',
            'company_name.string' => 'El nombre de la empresa debe ser texto'
        ];
    }
}
