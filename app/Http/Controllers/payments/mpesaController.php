<?php

namespace App\Http\Controllers\payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class mpesaController extends Controller
{
    // Generate the access token
    public function generateAccessToken()
    {
        $consumerKey = 'rHSSClhxwhNv0txIYGg5FhX3BbuCcQRA';

        $consumerSecret = 'pTgyun2lPXRADwyy';

        $credentials = $consumerKey . ':' . $consumerSecret;


        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $headers = ['Content-Type:application/json; charset=utf8'];

        $curl = curl_init($url);


        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_HEADER, false);

        curl_setopt($curl, CURLOPT_USERPWD, $credentials);


        $result = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        $result = json_decode($result);

        return $result->access_token;

        // $result = json_decode($curl_response);

        // return $access_token->access_token;
    }

    public function stkPush()
    {

        // $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        // $consumerKey = 'rHSSClhxwhNv0txIYGg5FhX3BbuCcQRA';

        // $consumerSecret = 'pTgyun2lPXRADwyy';

        // $credentials = $consumerKey . ':' . $consumerSecret;


        // $headers = ['Content-Type:application/json; charset=utf8'];

        // $businessShortCode = 174379;

        // $passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';

        // $timestamp = Carbon::now()->timestamp;

        // $amount = 1;

        // $partyA = 254745079253;

        // $password = base64_encode($businessShortCode . $passkey . $timestamp);

        // $curl = curl_init($url);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        // $curl_post_data = [
        //     "BusinessShortCode" => $businessShortCode,
        //     "Password" => $password,
        //     "Timestamp" => $timestamp,
        //     "TransactionType" => "CustomerPayBillOnline",
        //     "Amount" => $amount,
        //     "PartyA" => $partyA,
        //     "PartyB"=> $businessShortCode,
        //     "PhoneNumber" => $partyA,
        //     "CallBackURL"=> "https://06b3-105-230-177-161.ngrok-free.app/path",
        //     "AccountReference" => "Motech Limited",
        //     "TransactionDesc" => "Payment of tech products"
        // ];

        // $data_string = json_encode($curl_post_data);


        // curl_setopt($curl, CURLOPT_POST, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        // curl_setopt($curl , CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_USERPWD, $credentials);

        // $response = curl_exec($curl);
        // curl_close($curl);
        // return $response;




$ch = curl_init('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer BFy1qh6nyLHDC7AIzZJBySmojx6j',
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    "BusinessShortCode" => 174379,
    "Password" => "MTc0Mzc5YmZiMjc5ZjlhYTliZGJjZjE1OGU5N2RkNzFhNDY3Y2QyZTBjODkzMDU5YjEwZjc4ZTZiNzJhZGExZWQyYzkxOTIwMjMxMjMwMjExNjE5",
    "Timestamp" => "20231230211619",
    "TransactionType" => "CustomerPayBillOnline",
    "Amount" => 1,
    "PartyA" => 254745079253,
    "PartyB" => 174379,
    "PhoneNumber" => 254745079253,
    "CallBackURL" => "https://06b3-105-230-177-161.ngrok-free.app/path",
    "AccountReference" => "CompanyXLTD",
    "TransactionDesc" => "Payment of X"
  ]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response     = curl_exec($ch);
curl_close($ch);
echo $response;
    }

    public function pay()
    {
        return view('pay');
    }
}
