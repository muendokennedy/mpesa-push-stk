<?php

namespace App\Http\Controllers\payments;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class mpesaResponsesController extends Controller
{
    //Log the validation information to confirm that the validation endpoint has been hit

    public function validation(Request $request)
    {
        Log::info('The validation endopoint has been hit');
        Log::info($request->all());

        // This is return when you want to validate the transaction
        // return [
        //     'ResultCode' => '0',
        //     'ResultDesc' => 'Accepted'
        // ];

        // This is returned when you want to invalidate the transaction

        // [
        //     'ResultCode' => 'C2B00011',
        //     'ResultDesc' => 'Rejected'
        // ];

        // $data = file_get_contents('php://input');

        // Storage::disk('local')->put('validation.txt', $data);
    }
    public function confirmation(Request $request)
    {

        Log::info('The confirmation endopoint has been hit');
        Log::info($request->all());

        // $data = file_get_contents('php://input');

        // Storage::disk('local')->put('confirmation.txt', $data);
    }
    public function b2cCallback(Request $request)
    {
        Log::info('The confirmation endopoint has been hit');
        Log::info($request->all());
    }
    public function stkResponse(Request $request)
    {
        Log::info('The stk callback endopoint has been hit');
        Log::info($request->all());
    }

    public function reversalResponseResult(Request $request)
    {
        Log::info('The reversal response callack has been hit');
        Log::info($request->all());
    }

    public function statusResponseResult(Request $request)
    {
        Log::info('The status transaction callback has been hit');
        Log::info($request->all());
    }
}
