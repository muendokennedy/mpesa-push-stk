<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\payments\mpesaController;
use App\Http\Controllers\payments\mpesaResponsesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/v2/query/transaction', [mpesaController::class, 'queryTransaction']);
// Route::get('/v1/access/token', [mpesaController::class, 'generateAccessToken']);
// Route::post('/v1/stkpush', [mpesaController::class, 'stkPush']);
Route::get('/pay', [mpesaController::class, 'pay']);
// Route::get('/query/transaction', [mpesaController::class, 'query']);
Route::post('/get-token', [mpesaController::class,'getAccessToken']);
Route::post('/register-urls', [mpesaController::class,'registerURL']);
Route::post('/simulate-transaction', [mpesaController::class,'simulateTransaction']);
Route::post('/simulate-b2c', [mpesaController::class,'b2cRequest']);
Route::post('/simulate-stk', [mpesaController::class,'stkPush']);

Route::get('/b2c/request/pay', function(){
    return view('b2c');
});
Route::get('/b2c/push/stk', function(){
    return view('stk');
});




Route::post('/mobilemoney-payment-gateway/b2cresult', [mpesaResponsesController::class,'b2cCallback']);
Route::post('/mobilemoney-payment-gateway/validation', [mpesaResponsesController::class,'validation']);
Route::post('/mobilemoney-payment-gateway/confirmation', [mpesaResponsesController::class,'confirmation']);
Route::post('/mobilemoney-payment-gateway/stk', [mpesaResponsesController::class,'stkResponse']);

