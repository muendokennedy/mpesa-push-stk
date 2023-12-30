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

        $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        $access_token = $this->generateAccessToken();


        $headers = ['Content-Type:application/json', 'Authorization:Bearer ' . $access_token];

        $businessShortCode = 174379;

        $passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';

        $timestamp = date('YmdHis');

        $amount = 1;

        $partyA = 254745079253;

        $password = base64_encode($businessShortCode . $passkey . $timestamp);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $curl_post_data = [
            "BusinessShortCode" => $businessShortCode,
            "Password" => $password,
            "Timestamp" => $timestamp,
            "TransactionType" => "CustomerPayBillOnline",
            "Amount" => $amount,
            "PartyA" => $partyA,
            "PartyB"=> $businessShortCode,
            "PhoneNumber" => $partyA,
            "CallBackURL"=> "https://256c-105-230-177-161.ngrok-free.app/mpesa-payment-gateway/",
            "AccountReference" => "Motech Limited",
            "TransactionDesc" => "Payment of tech products"
        ];

        $data_string = json_encode($curl_post_data);


        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl , CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        return $response;
    }

    public function pay()
    {
        return view('pay');
    }
}
