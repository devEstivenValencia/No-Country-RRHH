<?php

namespace App\Http\Requests;

use App\Exceptions\CustomAuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class CustomFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        $nested = [];
        foreach ($errors as $key => $value) {
            $nested = Arr::undot($errors, $key, $value);
        }

        throw new HttpResponseException(
            response()->json([
                'error' => "BAD_REQUEST",
                'message' => $errors[array_key_first($errors)][0],
                'errors' => $nested
            ], 400)
        );
    }

    public function failedAuthorization()
    {
        throw new CustomAuthorizationException();
    }
}
