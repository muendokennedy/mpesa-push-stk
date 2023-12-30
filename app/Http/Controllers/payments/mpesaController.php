<?php

namespace App\Http\Controllers\payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

    }
}
