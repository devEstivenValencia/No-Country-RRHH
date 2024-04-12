<?php

namespace App\Http\Requests;

use App\Classes\CustomEncrypter;
use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $allowed = ['email', 'password', 'phone_number'];
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
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'
            ],
            'phone_number' => 'required',
            'company_name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email ingresado no es un email válido',
            'email.unique' => 'El email ya ha sido registrado',
            'email.exists' => 'El email no está registrado',
            'password.required' => 'El password es obligatorio',
            'password.min' => 'La contraseña debe tener al menos :min caracteres',
            'password.regex' => 'La contraseña debe contener al menos una letra minúscula, una letra mayúscula, un dígito, un carácter especial y tener una longitud mínima de 8 caracteres.',
            'phone_number.required' => 'El número de contacto es obligatorio',
            'company_name.required' => 'El nombre de la empresa es obligatorio'
        ];
    }
}
