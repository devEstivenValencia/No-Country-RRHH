<?php

namespace App\Http\Requests;

use App\Classes\CustomEncrypter;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class CompanyUpdateRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $allowed = ['contact'];
        $dataToDecrypt = array_intersect_key($this->toArray(), array_flip($allowed));

        $dataDecrypted = CustomEncrypter::decrypt($dataToDecrypt);

        $this->merge($dataDecrypted);
    }

    public function authorize(): bool
    {
        return true;
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

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        $nested = [];
        foreach ($errors as $key => $value) {
            $nested = Arr::undot($errors, $key, $value);
        }

        throw new HttpResponseException(
            response()->json([
                'error' => "BAD_REQUEST",
                'errors' => $nested
            ], 400)
        );
    }
}