<?php

namespace App\Http\Requests;

use App\Classes\CustomEncrypter;

class CompanyUpdateRequest extends CustomFormRequest
{
    protected function prepareForValidation()
    {
        /* $allowed = ['contact'];
        $dataToDecrypt = array_intersect_key($this->toArray(), array_flip($allowed));

        $dataDecrypted = CustomEncrypter::decrypt($dataToDecrypt); */

        $keyToDecrypt = [
            'contact' => [
                'email',
                'phone'
            ]
        ];

        $dataDecrypted = CustomEncrypter::recurseSpecificSchema(
            array(CustomEncrypter::class, 'decryptString'),
            $this->toArray(),
            $keyToDecrypt
        );

        $this->merge($dataDecrypted);
    }

    public function authorize(): bool
    {
        return $this->user()->tokenCan('update-company');
    }

    public function rules(): array
    {
        return [
            'contact.email' => 'required|email',
            'contact.phone' => 'required',
            'company_name' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'contact.email.required' => 'El email de contacto es obligatorio',
            'contact.email.email' => 'El email de contacto ingresado no es un email válido',

            'contact.phone.required' => 'El número de contacto es obligatorio',

            'company_name.required' => 'El nombre de la empresa es obligatorio'
        ];
    }
}
