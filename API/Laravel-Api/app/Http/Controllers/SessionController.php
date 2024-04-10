<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\SessionRequest;

class SessionController extends Controller
{
    public function store(SessionRequest $request)
    {
    }

    public function validate_token()
    {
        //
    }

    public function destroy()
    {
        //
    }
}
