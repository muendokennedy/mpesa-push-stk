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

Route::post('/get-token', [mpesaController::class,'getAccessToken']);
Route::post('/register-urls', [mpesaController::class,'registerURL']);
Route::post('/simulate-transaction', [mpesaController::class,'simulateTransaction']);
Route::post('/simulate-b2c', [mpesaController::class,'b2cRequest']);
Route::post('/simulate-stk', [mpesaController::class,'stkPush']);
Route::post('/reverse-transaction', [mpesaController::class,'reverseTransaction']);
Route::post('/transaction-status', [mpesaController::class,'checkTransactionStatus']);

Route::get('/', function(){
    return view('pay');
});

Route::get('/b2c/request/pay', function(){
    return view('b2c');
});
Route::get('/b2c/push/stk', function(){
    return view('stk');
});
Route::get('/c2b/reversal', function(){
    return view('reversal');
});
Route::get('/c2b/transaction-status', function(){
    return view('status');
});


Route::post('/mobilemoney-payment-gateway/b2cresult', [mpesaResponsesController::class,'b2cCallback']);
Route::post('/mobilemoney-payment-gateway/validation', [mpesaResponsesController::class,'validation']);
Route::post('/mobilemoney-payment-gateway/confirmation', [mpesaResponsesController::class,'confirmation']);
Route::post('/mobilemoney-payment-gateway/stk', [mpesaResponsesController::class,'stkResponse']);
Route::post('/mobilemoney-payment-gateway/Reversal/result', [mpesaResponsesController::class,'reversalResponseResult']);
Route::post('/mobilemoney-payment-gateway/TransactionStatus/result', [mpesaResponsesController::class,'statusResponseResult']);

