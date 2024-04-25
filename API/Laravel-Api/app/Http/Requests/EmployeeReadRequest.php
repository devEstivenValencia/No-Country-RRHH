<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeReadRequest extends CustomFormRequest
{
    public function authorize(): bool
    {
        return $this->user()->tokenCan('read-employee');
    }

    public function rules(): array
    {
        return [];
    }
}
