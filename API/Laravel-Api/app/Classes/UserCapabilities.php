<?php

namespace App\Classes;

class UserCapabilities
{
    static public function company(): array
    {
        return [
            'update-company',
            'create-employee',
            'update-employee',
            'Read-employee'
        ];
    }

    static public function employee(): array
    {
        return [];
    }
}