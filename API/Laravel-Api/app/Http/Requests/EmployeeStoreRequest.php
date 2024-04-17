<?php

namespace App\Http\Requests;

use App\Classes\CustomEncrypter;

class EmployeeStoreRequest extends CustomFormRequest
{
    protected function prepareForValidation(): void
    {
        $allowed = ['credentials', 'contact', 'id_legal', 'address', 'contact'];
        $dataToDecrypt = array_intersect_key($this->toArray(), array_flip($allowed));

        $dataDecrypted = CustomEncrypter::decrypt($dataToDecrypt);

        $this->merge($dataDecrypted);
    }

    public function authorize(): bool
    {
        return $this->user->tokenCan('create_employee');
    }

    public function rules(): array
    {
        return [
            'name.required' => 'El nombre del empleado es obligatorio',
            'id_legal.required' => 'El id legal del empleado es obligatorio',
            'employee_code.required' => 'El código del empleado es obligatorio',
            'address.required' => 'La dirección del empleado es obligatorio',

            'contact.email.required' => 'El email de contacto es obligatorio',
            'contact.email.email' => 'El email de contacto ingresado no es un email válido',

            'contact.phone.required' => 'El número de contacto es obligatorio',

            'state.required' => 'El estado es obligatorio',
            'role.required' => 'El rol del empleado es obligatorio',
        ];
    }
}
