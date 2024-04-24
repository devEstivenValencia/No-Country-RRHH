<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class CustomAuthorizationException extends AuthorizationException
{
    public function render($request)
    {
        return response()->json([
            'error' => 'UNAUTHENTICATED',
            'message' => 'usuario no autorizado'
        ], 401);
    }
}
