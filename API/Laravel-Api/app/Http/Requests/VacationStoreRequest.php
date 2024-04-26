<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VacationStoreRequest extends CustomFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tokenCan('send-vacation');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'initial_date' => 'required|date',
            'final_date' => 'required|date',

        ];
    }

    public function messages(): array
    {
        return [
            'initial_date.required' => 'La fecha de inicio de las vacaciones son requeridas',
            'initial_date.date' => 'formato de inicio vacaciones no valido',
            'final_date.required' => 'La fecha de finalización de las vacaciones son requeridas',
            'final_date.date' => 'Formato de finalización de vacaciones no valido'
        ];
    }
}
