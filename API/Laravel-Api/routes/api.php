<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Employee;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EncryptController;
use App\Http\Controllers\SessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('session/login', [SessionController::class, 'store']);
Route::post('session/validate', [SessionController::class, 'validate_token'])->middleware('auth:sanctum');
Route::delete('session/logout', [SessionController::class, 'destroy'])->middleware('auth:sanctum');

Route::post('account/enterprise/register', [CompanyController::class, 'store']);
Route::put('account/enterprise/modify', [CompanyController::class, 'update'])->middleware('auth:sanctum');

Route::put('account/employee/register', [EmployeeController::class, 'store'])->middleware('auth:sanctum');
Route::put('account/employee/modify', [EmployeeController::class, 'update'])->middleware('auth:sanctum');

Route::post('account/employee/register', [EmployeeController::class, 'store'])->middleware('auth:sanctum');

Route::post('encrypt', [EncryptController::class, 'encrypt_data']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
