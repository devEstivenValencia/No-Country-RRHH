<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeReadRequest;
use App\Models\User;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\KeyManager;

class EmployeeController extends Controller
{

    public function index(EmployeeReadRequest $request): JsonResponse
    {
        $request->validated();
        $company = Company::where('user_id', Auth::user()->id)->first();

        if ($company) {

            $employees = $company->employees()->get();

            $employeesData = [];
            foreach ($employees as $employee) {
                $employeesData[] = [
                    'employee_id' => $employee->id,
                    'name' => $employee->name,
                    'dni' => $employee->id_legal,
                    "address" => $employee->address,
                    "contact" => $employee->contact,
                ];
            }

            $response = [
                'company_id' => $company->id,
                'company_name' => $company->company_name,
                'employees' => $employeesData,
            ];

            return response()->json($response);
        } else {
            return response()->json(['message' => 'No se encontró la compañía asociada al usuario.'], 404);
        }
    }


    public function store(EmployeeStoreRequest $request): JsonResponse
    {
        $company = Company::where('user_id', '=', Auth::user()->id)->first();
        $data = $request->validated();
        $user = new User();
        $user->id = Str::uuid(36);

        $user->email = $data['employee']['credentials']['email'];
        $user->email_verified_at = now();
        $user->password = Hash::make($data['employee']['credentials']['password']);
        $user->remember_token = Str::random(10);
        $user->save();
        $employee = new Employee();
        $employee->user_id = $user->id;
        $employee->company_id = $company->id;
        $employee->name = $data['employee']['name'];
        $employee->id_legal = $data['employee']['dni'];
        $employee->address = $data['employee']['address'];
        $employee->contact = $data['employee']['contact'];
        $employee->admission_date = $data['employee']['admission_date'];
        $employee->role = $data['employee']['role'];

        $employee->save();
        return response()->json(['success' => true], 200);
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
