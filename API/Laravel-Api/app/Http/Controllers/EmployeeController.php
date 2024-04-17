<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function store(EmployeeStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        return response()->json(['message' => 'empleado creado'], 200);
    }

    public function update(EmployeeUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $employee = Employee::where('company_id', '=', Auth::user()->id)
            ->where('user_id', '=', $data['employee_id'])->first();

        return response()->json(['message' => 'empleado actualizado'], 200);
    }
}
