<?php

namespace App\Http\Controllers;

use App\Classes\CustomAlert;
use App\Http\Requests\VacationIndexRequest;
use App\Http\Requests\VacationStoreRequest;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Vacation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VacationController extends Controller
{
    public function index(VacationIndexRequest $request): JsonResponse
    {
        $request->validated();
        $company = Company::where('user_id', Auth::user()->id)->first();


        if ($company) {/* 
            $key = KeyManager::find($request->query('key_id'));
            $sharedKey = base64_decode($key->key);
            $key->delete(); */

            $employees = $company->employees()->get();

            $employeesData = [];
            foreach ($employees as $employee) {
                $vacation = Vacation::where('employee_id', '=', $employee->id)->first();

                if ($vacation) {
                    $employeesData[] = [
                        'id' => $employee->user_id,
                        'name' => $employee->name,
                        'email' => $employee->contact['email'],
                        'role' => $employee->role,
                        "dates" => $vacation->initial_date . ' > ' . $vacation->final_date
                    ];
                }
            }

            $response = [
                'employees' => $employeesData,
            ];

            return response()->json($response);
        } else {
            return response()->json(['message' => 'No se encontró la compañía asociada al usuario.'], 404);
        }
    }

    public function store(VacationStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $employee = Employee::where('user_id', '=', Auth::user()->id)->first();


        $vacation = Vacation::where('employee_id', '=', $employee->id)->first();
        if ($vacation) $vacation->delete();

        $vacation = new Vacation();
        $vacation->id = Str::uuid(36);
        $vacation->employee_id = $employee->id;
        $vacation->initial_date = $data['initial_date'];
        $vacation->final_date = $data['final_date'];
        $vacation->state = 'approved';
        $vacation->created_at = now();
        $vacation->save();

        event(new CustomAlert(
            $employee->company_id,
            Str::uuid(36),
            'new-vacation'
        ));

        return response()->json(['success' => true], 200);
    }
    //
}
