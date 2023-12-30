<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\payments\mpesaController;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/v1/access/token', [mpesaController::class, 'generateAccessToken']);
Route::post('/v1/stkpush', [mpesaController::class, 'stkPush']);
Route::get('/pay', [mpesaController::class, 'pay']);
