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

Route::post('enterprise/employee/create', [EmployeeController::class, 'store'])->middleware('auth:sanctum');
Route::put('enterprise/employee/modify', [EmployeeController::class, 'update'])->middleware('auth:sanctum');

Route::post('encrypt', [EncryptController::class, 'encrypt_data']);


Route::post('encryptkey', function (Request $request) {
    $publicKey = $request->get('publicKey');

    $sharedKey = base64_decode(Env('SECOND_KEY'));

    openssl_public_encrypt($sharedKey, $encryptedKey, $publicKey, OPENSSL_PKCS1_PADDING);

    return response()->json([
        'encryptedKey' => base64_encode($encryptedKey),
        'key_id' => 'el uuid de la clave', // uuid string
    ], 200);
});
/*
Route::post('decrypt', function (Request $request) {
    $handshakeId = $request->get('handshakeId');

    $sharedKey = base64_decode(Env('SECOND_KEY'));

    // get the encrypted data from client and base 64 decode it
    $encryptedMsg = base64_decode($request->get('encryptedData'));

    // get the first 16 bytes from the payload (must match the IV byte length)
    $iv = mb_substr($encryptedMsg, 0, 16, '8bit');

    // get the encrypted value part (should match the rest of the payload)
    $encrypted = mb_substr($encryptedMsg, 16, null, '8bit');

    // decrypt the value
    $decryptedData = openssl_decrypt(
        $encrypted,
        'aes-256-cbc',
        $sharedKey,
        OPENSSL_RAW_DATA,
        $iv
    );

    return response()->json([
        'decryptedData' => $decryptedData
    ], 200);
});
 */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
