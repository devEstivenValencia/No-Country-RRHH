<?php

namespace App\Http\Requests;

use Illuminate\Encryption\Encrypter;
use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $encrypter = new Encrypter(base64_decode(Env('SECOND_KEY')), 'AES-256-CBC');
        $this->merge([
            'email' => $encrypter->decrypt($this->email),
            'password' => $encrypter->decrypt($this->password),
            'password_confirmation'
            => $encrypter->decrypt($this->password_confirmation),
        ]);
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
                'confirmed',
                'regex:/^(?=.?[A-Z])(?=.?[a-z])(?=.?[0-9])(?=.?[#?!@$%^&*-]).{8,}$/'
            ],
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
            'password.confirmed' => 'La confirmación de contraseña no coincide'
        ];
    }
}
