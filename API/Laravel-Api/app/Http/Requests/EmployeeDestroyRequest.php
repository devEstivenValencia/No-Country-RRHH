<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeDestroyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->tokenCan('delete-employee');
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'employee_idrequired' => 'El id del empleado es obligatorio',
            'employee_idstring' => 'El id del empleado debe de ser texto'
        ];
    }
}
