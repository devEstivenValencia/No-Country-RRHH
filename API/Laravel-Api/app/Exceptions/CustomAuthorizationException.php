<?php

namespace App\Classes;

use Illuminate\Auth\Access\AuthorizationException;

class CustomAuthorizationException extends AuthorizationException
{
    public function render($request)
    {
        return response()->json([
            'error' => 'UNAUTHENTICATED'
        ], 401);
    }
}
