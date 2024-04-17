<?php

namespace App\Http\Requests;

use App\Classes\CustomEncrypter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class CompanyRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $allowed = ['credentials', 'contact'];
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
            'credentials.email' => 'required|email|unique:users,email',
            'credentials.password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'
            ],
            'contact.email' => 'required|email',
            'contact.phone' => 'required',
            'company_name' => 'required'
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
