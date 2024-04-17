<?php

namespace App\Http\Requests;

use App\Classes\CustomEncrypter;
use Illuminate\Foundation\Http\FormRequest;

class SessionRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $dataDecrypted = CustomEncrypter::decrypt($this->toArray());

        $this->merge($dataDecrypted);
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
