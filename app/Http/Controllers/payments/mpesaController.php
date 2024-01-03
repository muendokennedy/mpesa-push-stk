<?php

namespace App\Http\Controllers\payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class mpesaController extends Controller
{

    public function getAccessToken()
    {
        $url = env('MPESA_ENV') == 0

        ? 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'

        : 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $curl = curl_init($url);

        curl_setopt_array($curl,
            array(
                CURLOPT_HTTPHEADER => ['Content-Type: application/json; charset=utf8'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => false,
                CURLOPT_USERPWD => env('MPESA_CONSUMER_KEY') . ':' . env('MPESA_CONSUMER_SECRET')
            )
        );

        $response = json_decode(curl_exec($curl));

        curl_close($curl);

        return $response->access_token;
    }

    public function registerURL()
    {
        $body = array(
            'ShortCode' => env('MPESA_SHORTCODE'),
            'ResponseType' => 'Completed',
            'ConfirmationURL' => env('MPESA_TEST_URL') . '/api/confirmation',
            'ValidationURL' => env('MPESA_TEST_URL') . '/api/validation'
        );

        $url = env('MPESA_ENV') == 0

        ? 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl'

        : 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl';

        return $this->makeHttp($url, $body);
    }

    public function simulateTransaction(Request $request)
    {
        $response = array(
            'Msisdn' => '',
            'Amount' => $request->amount,
            'BillRefNumber' => $request->account,
            'CommandID' => 'CustomerPayBillOnline'
        );

        $url = env('MPESA_ENV') == 0

        ? 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate'

        : 'https://api.safaricom.co.ke/mpesa/c2b/v1/simulate';

        return $this->makeHttp($url, $response);
    }

    private function makeHttp($url, $body)
    {
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => array('Content-Type: application/json', 'Authorization:Bearer ' .$this->getAccessToken()),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($body)
            )
        );

        $curl_response = curl_exec($curl);

        curl_close($curl);

        return $curl_response;
    }

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
            "CallBackURL"=> "https://04ab-105-230-177-161.ngrok-free.app/mpesa-payment-gateway/",
            "AccountReference" => "Motech Limited",
            "TransactionDesc" => "Payment of tech products"
        ];

        $data_string = json_encode($curl_post_data);


        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl , CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        $data = json_decode($response);

        // $CheckoutRequestID = $data->CheckoutRequestID;

        // $ResponseCode = $data->ResponseCode;

        return "<h1>" . "The checkoutRequestId is: " . $data->CheckoutRequestID . " and the access  token  is: " . $access_token . "</h1>";

    }

    public function queryTransaction()
    {

        $query_url = "https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query";

        $access_token = "sGoaMJkmEWcMD9jOPRX6TcNkG0Qb";

        $businessShortCode = 174379;

        $passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';

        $timestamp = date('YmdHis');

        $password = base64_encode($businessShortCode . $passkey . $timestamp);

        $checkoutRequestId = "ws_CO_31122023125630907745079253";
        // Initiating the transaction

        $curl = curl_init();

        $headers = ['Content-Type:application/json', 'Authorization:Bearer  ' . $access_token];

        curl_setopt($curl, CURLOPT_URL, $query_url);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_POST, true);

        $curl_post_data = [
            "BusinessShortCode" => $businessShortCode,
            "Password" => $password,
            "Timestamp" => $timestamp,
            "CheckoutRequestID" => $checkoutRequestId,
        ];

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    public function pay()
    {
        return view('pay');
    }
    public function query()
    {
        return view('query');
    }
}
