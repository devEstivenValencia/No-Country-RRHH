<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Http\FormRequest;

class SessionRequest extends FormRequest
{
    // protected function prepareForValidation()
    // {
    //     $this->merge([
    //         'email' => Crypt::decryptString($this->email),
    //         'password' => Crypt::decryptString($this->password),
    //     ]);
    // }

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
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
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
