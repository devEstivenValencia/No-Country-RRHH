<?php

namespace App\Http\Requests;

class VacationIndexRequest extends CustomFormRequest
{
    public function authorize(): bool
    {
        return $this->user()->tokenCan('get-all-vacations');
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
