<?php

namespace App\Http\Requests;

use App\Classes\CustomEncrypter;
use App\Models\KeyManager;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeeStoreRequest extends CustomFormRequest
{
    protected function prepareForValidation(): void
    {
        $keyToDecrypt = [
            'company_id',
            'employee' => [
                'dni',
                'address' => [
                    'country',
                    'province',
                    'city',
                    'address',
                ],
                'contact' => [
                    'email',
                    'phone'
                ],
                'credentials' => [
                    'email', 'password'
                ]
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
        return $this->user()->tokenCan('create-employee');
    }

    public function rules(): array
    {
        return
            [
                'company_id' => 'required|string',
                'employee.name' => 'required|string',
                'employee.dni' => 'required|string',
                'employee.address.country' => 'required|string',
                'employee.address.province' => 'required|string',
                'employee.address.city' => 'required|string|',
                'employee.address.address' => 'required|string',

                'employee.contact.email' => 'required|email',
                'employee.contact.phone' => 'required|string',
                'employee.credentials.email' => 'required|email|unique:users,email',
                'employee.credentials.password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'
                ],
                'employee.admission_date' => 'required|date_format:Y-m-d',
                'employee.role' => 'required|string'
            ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'El id de la companía es obligatorio',
            'employee.name.required' => 'El nombre del empleado es obligatorio',
            'employee.name.string' => 'El nombre del empleado debe de ser texto',
            'employee.dni.required' => 'El dni del empleado es obligatorio',
            'employee.dni.string' => 'El dni del empleado debe ser texto',
            'employee.address.country.required' => 'El país del empleado debe ser obligatorio',
            'employee.address.country.string' => 'El país del empleado debe ser texto',
            'employee.address.province.required' => 'La provincia es obligatoria',
            'employee.address.province.string' => 'La provincia del empleado debe ser texto',
            'employee.address.city.string' => 'La ciudad del empleado debe ser texto',
            'employee.address.city.required' => 'La ciudad del empleado es obligatoria',
            'employee.address.address.string' => 'La dirección del empleado debe ser texto',
            'employee.address.address.required' => 'La dirección del empleado es obligatoria',
            'employee.contact.email.email' => 'El email de contacto ingresado no es un email válido',
            'employee.contact.email.required' => 'El email de contacto es obligatorio',
            'employee.contact.phone.required' => 'El teléfono del empleado es obligatorio',
            'employee.contact.phone.string' => 'El teléfono del empleado debe ser texto',
            'employee.admission_date.required' => 'La fecha de contratación del empleado es obligatorio',
            'employee.admission_date.date_format' => 'La fecha de contratación del empleado debe ser una fecha válida',
            'employee.role.required' => 'El rol del empleado es obligatorio',
            'employee.role.string' => 'El rol del empleado debe ser texto',

            'employee.credentials.email.required' => 'El email es obligatorio',
            'employee.credentials.email.email' => 'El email ingresado no es un email válido',
            'employee.credentials.email.unique' => 'El email ya ha sido registrado',
            'employee.credentials.email.exists' => 'El email no está registrado',

            'employee.credentials.password.required' => 'El password es obligatorio',
            'employee.credentials.password.min' => 'La contraseña debe tener al menos :min caracteres',
            'employee.credentials.password.regex' => 'La contraseña debe contener al menos una letra minúscula, una letra mayúscula, un dígito, un carácter especial y tener una longitud mínima de 8 caracteres.',
        ];
    }
}
