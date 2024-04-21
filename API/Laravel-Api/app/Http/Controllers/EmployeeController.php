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
        $userId = Auth::id();
        $data = $request->validated();

        $employee = new Employee();
        $employee->user_id = $userId;
        $employee->company_id = $data['company_id'];
        $employee->name = $data['employee']['name'];
        $employee->dni = $data['employee']['dni'];
        $employee->address['country'] = $data['employee']['address']['country'];
        $employee->address['province'] = $data['employee']['address']['province'];
        $employee->address['province'] = $data['employee']['address']['city'];
        $employee->address['address'] = $data['employee']['address']['address'];
        $employee->address['zipcode'] = $data['employee']['address']['zipcode'];
        $employee->contact['email'] = $data['employee']['contact']['email'];
        $employee->contact['phone'] = $data['employee']['contact']['phone'];
        $employee->contact['email'] = $data['employee']['contact']['email'];
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
            /* $employee->name = isset($dataEmployee['name']) ? $dataEmployee['name'] : $employee->name;
            $employee->legal_id = isset($dataEmployee['dni']) ? $dataEmployee['dni'] : $employee->legal_id;
            $employee->admission_date = isset($dataEmployee['admission_date']) ? $dataEmployee['admission_date'] : $employee->name;
            $employee->name = isset($dataEmployee['name']) ? $dataEmployee['name'] : $employee->name;
            $employee->name = isset($dataEmployee['name']) ? $dataEmployee['name'] : $employee->name; */
            $employee->save();

            return response()->json(['message' => 'empleado actualizado', "data" => $dataEmployee], 200);
        }
        return response()->json(['error' => 'BAD_REQUEST'], 400);
    }
}
