<?php

namespace App\Http\Controllers\payments;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class mpesaResponsesController extends Controller
{
    //Log the validation information to confirm that the validation endpoint has been hit

    public function validatation(Request $request)
    {
        Log::info('The validation endopoint has been hit');
        Log::info($request->all());
    }
    public function confirmation(Request $request)
    {
        Log::info('The confirmation endopoint has been hit');
        Log::info($request->all());
    }
}
