<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Company;
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
        $company = Company::where('user_id', '=', Auth::user()->id)->first();

        $dataEmployee = $data['employee'];

        $employee = Employee::where('company_id', '=', $company->id)
            ->where('user_id', '=', $dataEmployee['employee_id'])->first();

        if ($employee) {
            $employee->name = isset($dataEmployee['name']) ? $dataEmployee['name'] : $employee->name;
            $employee->id_legal = isset($dataEmployee['dni']) ? $dataEmployee['dni'] : $employee->id_legal;

            $employee->address = array_merge(
                isset($employee->address) ? $employee->address : [],
                isset($dataEmployee['address']) ? $dataEmployee['address'] : []
            );

            $employee->contact = array_merge(
                isset($employee->address) ? $employee->address : [],
                isset($dataEmployee['contact']) ? $dataEmployee['contact'] : []
            );

            $employee->admission_date = $dataEmployee['admission_date'];
            $employee->role = $dataEmployee['role'];
            $employee->save();

            return response()->json(['success' => true], 200);
        }
        return response()->json(['error' => 'BAD_REQUEST'], 400);
    }
}
