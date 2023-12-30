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

        $credentials = base64_encode($consumerKey . ':' . $consumerSecret);


        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);


        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic' . $credentials));

        curl_setopt($curl, CURLOPT_HEADER, false);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $curl_response = curl_exec($curl);

        $access_token = json_decode($curl_response);

        return $access_token->access_token;
    }

    public function stkPush()
    {

    }
}
