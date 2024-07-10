<?php

namespace App\Http\Requests;

use App\Classes\CustomEncrypter;
use App\Models\KeyManager;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompanyUpdateRequest extends CustomFormRequest
{
    protected function prepareForValidation()
    {
        /* $allowed = ['contact'];
        $dataToDecrypt = array_intersect_key($this->toArray(), array_flip($allowed));

        $dataDecrypted = CustomEncrypter::decrypt($dataToDecrypt); */

        $keyToDecrypt = [
            'location' => [
                'country',
                'province',
                'city',
                'address',
            ],
            'contact' => [
                'email',
                'phone'
            ]
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
        return $this->user()->tokenCan('update-company');
    }

    public function rules(): array
    {
        return [
            'contact.email' => 'required|email',
            'contact.phone' => 'required|string',

            'location.country' => 'required|string',
            'location.province' => 'required|string',
            'location.city' => 'required|string|',
            'location.address' => 'required|string',
            /* 
            'location.zipcode' => 'required|string', */
            'sector' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'contact.email.required' => 'El email de contacto es obligatorio',
            'contact.email.email' => 'El email de contacto ingresado no es un email válido',
            'contact.phone.required' => 'El teléfono del empleado es obligatorio',
            'contact.phone.string' => 'El teléfono del empleado debe ser texto',

            'location.country.required' => 'El país del empleado debe ser obligatorio',
            'location.country.string' => 'El país del empleado debe ser texto',
            'location.province.required' => 'La provincia es obligatoria',
            'location.province.string' => 'La provincia del empleado debe ser texto',
            'location.city.string' => 'La ciudad del empleado debe ser texto',
            'location.city.required' => 'La ciudad del empleado es obligatoria',
            'location.address.string' => 'La dirección del empleado debe ser texto',
            'location.address.required' => 'La dirección del empleado es obligatoria',

            'sector.required' => 'El rol del empleado es obligatorio',
            'sector.string' => 'El rol del empleado debe ser texto',
        ];
    }
}
