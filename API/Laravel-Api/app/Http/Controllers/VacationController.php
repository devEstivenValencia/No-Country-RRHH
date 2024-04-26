<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Vacation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\VacationStoreRequest;
use Illuminate\Http\JsonResponse;

class VacationController extends Controller
{
    public function store(VacationStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $vacation = new Vacation();
        $vacation->id = Str::uuid(36);
        $vacation->employee_id = $employee->id;
        $vacation->initial_date = $data['initial_date'];
        $vacation->initial_date = $data['final_date'];
        $vacation->comments = $data['comments'];
        $vacation->state = $data['state'];
        return response()->json(['success' => true], 200);
    }
    //
}
