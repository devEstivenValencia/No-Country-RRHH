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
            'read-employee',
            'delete-employee',
            'get-all-vacations',
        ];
    }

    static public function employee(): array
    {
        return [
            'send-vacation'
        ];
    }
}
