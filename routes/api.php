<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\payments\mpesaController;
use App\Http\Controllers\payments\mpesaResponsesController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Create some api routes for the confirmation and the validation URLs

Route::post('/mobilemoney-payment-gateway/b2cresult', [mpesaResponsesController::class,'b2cCallback']);
Route::post('/mobilemoney-payment-gateway/validation', [mpesaResponsesController::class,'validation']);
Route::post('/mobilemoney-payment-gateway/confirmation', [mpesaResponsesController::class,'confirmation']);
Route::post('/mobilemoney-payment-gateway/stk', [mpesaResponsesController::class,'stkResponse']);
Route::post('/mobilemoney-payment-gateway/Reversal/result', [mpesaResponsesController::class,'reversalResponseResult']);
Route::post('/mobilemoney-payment-gateway/TransactionStatus/result', [mpesaResponsesController::class,'statusResponseResult']);
