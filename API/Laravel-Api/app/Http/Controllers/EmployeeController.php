<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;

class EmployeeController extends Controller
{
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
        $employee->user_id =
            $user->id;
        $employee->company_id = $company->id;
        $employee->name = $data['employee']['name'];
        $employee->id_legal = $data['employee']['dni'];
        $employee->address = $data['employee']['address'];
        $employee->contact = $data['employee']['contact'];
        $employee->admission_date = $data['employee']['admission_date'];
        $employee->role = $data['employee']['role'];
        // $employee->address = [
        //     'country' => $data['employee']['address']['country'], 'province' => $data['employee']['address']['province'],
        //     'city' => $data['employee']['address']['city'],
        //     'address' => $data['employee']['address']['address'],
        //     'zipcode' => $data['employee']['address']['zipcode'],
        // ];
        // $employee->contact = ['email' => $data['employee']['contact']['email'], 'phone' => $data['employee']['contact']['phone']];



        // $employee->address['country'] = $data['employee']['address']['country'];
        // $employee->address['province'] = $data['employee']['address']['province'];
        // $employee->address['province'] = $data['employee']['address']['city'];
        // $employee->address['address'] = $data['employee']['address']['address'];
        // $employee->address['zipcode'] = $data['employee']['address']['zipcode'];


        // $employee->contact['email'] = $data['employee']['contact']['email'];
        // $employee->contact['phone'] = $data['employee']['contact']['phone'];
        // $employee->credentials['email'] = $data['employee']['credentials']['email'];
        // $employee->credentials['password'] = $data['employee']['credentials']['password'];

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
