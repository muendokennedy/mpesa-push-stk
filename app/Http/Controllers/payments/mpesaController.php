<?php

namespace App\Http\Controllers\payments;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

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
        Log::info('The log is working from my application');
        $body = array(
            'ShortCode' => 600992,
            'ResponseType' => 'Completed',
            'ConfirmationURL' => env('MPESA_TEST_URL') . '/mobilemoney-payment-gateway/confirmation',
            'ValidationURL' => env('MPESA_TEST_URL') . '/mobilemoney-payment-gateway/validation'
        );

        $url = env('MPESA_ENV') == 0

        ? 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl'

        : 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl';

        return $this->makeHttp($url, $body);
    }

    public function simulateTransaction(Request $request)
    {
        $jsonData = $request->getContent();

        $transactionData = json_decode($jsonData, true);

        $response = array(
            'ShortCode' => env('MPESA_SHORTCODE'),
            'Msisdn' => env('MPESA_TEST_MISDN'),
            'Amount' => $transactionData['amount'],
            'BillRefNumber' => $transactionData['account'],
            'CommandID' => 'CustomerPayBillOnline'
        );

        $url = env('MPESA_ENV') == 0

        ? 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate'

        : 'https://api.safaricom.co.ke/mpesa/c2b/v1/simulate';

        return $this->makeHttp($url, $response);
    }

    public function b2cRequest(Request $request)
    {
        $jsonData = $request->getContent();

        $transactionData = json_decode($jsonData, true);

        $initiatorPassword = 'Safaricom999!*!';

        $publicKey = '-----BEGIN CERTIFICATE-----
        MIIGgDCCBWigAwIBAgIKMvrulAAAAARG5DANBgkqhkiG9w0BAQsFADBbMRMwEQYK
        CZImiZPyLGQBGRYDbmV0MRkwFwYKCZImiZPyLGQBGRYJc2FmYXJpY29tMSkwJwYD
        VQQDEyBTYWZhcmljb20gSW50ZXJuYWwgSXNzdWluZyBDQSAwMjAeFw0xNDExMTIw
        NzEyNDVaFw0xNjExMTEwNzEyNDVaMHsxCzAJBgNVBAYTAktFMRAwDgYDVQQIEwdO
        YWlyb2JpMRAwDgYDVQQHEwdOYWlyb2JpMRAwDgYDVQQKEwdOYWlyb2JpMRMwEQYD
        VQQLEwpUZWNobm9sb2d5MSEwHwYDVQQDExhhcGljcnlwdC5zYWZhcmljb20uY28u
        a2UwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCotwV1VxXsd0Q6i2w0
        ugw+EPvgJfV6PNyB826Ik3L2lPJLFuzNEEJbGaiTdSe6Xitf/PJUP/q8Nv2dupHL
        BkiBHjpQ6f61He8Zdc9fqKDGBLoNhNpBXxbznzI4Yu6hjBGLnF5Al9zMAxTij6wL
        GUFswKpizifNbzV+LyIXY4RR2t8lxtqaFKeSx2B8P+eiZbL0wRIDPVC5+s4GdpFf
        Y3QIqyLxI2bOyCGl8/XlUuIhVXxhc8Uq132xjfsWljbw4oaMobnB2KN79vMUvyoR
        w8OGpga5VoaSFfVuQjSIf5RwW1hitm/8XJvmNEdeY0uKriYwbR8wfwQ3E0AIW1Fl
        MMghAgMBAAGjggMkMIIDIDAdBgNVHQ4EFgQUwUfE+NgGndWDN3DyVp+CAiF1Zkgw
        HwYDVR0jBBgwFoAU6zLUT35gmjqYIGO6DV6+6HlO1SQwggE7BgNVHR8EggEyMIIB
        LjCCASqgggEmoIIBIoaB1mxkYXA6Ly8vQ049U2FmYXJpY29tJTIwSW50ZXJuYWwl
        MjBJc3N1aW5nJTIwQ0ElMjAwMixDTj1TVkRUM0lTU0NBMDEsQ049Q0RQLENOPVB1
        YmxpYyUyMEtleSUyMFNlcnZpY2VzLENOPVNlcnZpY2VzLENOPUNvbmZpZ3VyYXRp
        b24sREM9c2FmYXJpY29tLERDPW5ldD9jZXJ0aWZpY2F0ZVJldm9jYXRpb25MaXN0
        P2Jhc2U/b2JqZWN0Q2xhc3M9Y1JMRGlzdHJpYnV0aW9uUG9pbnSGR2h0dHA6Ly9j
        cmwuc2FmYXJpY29tLmNvLmtlL1NhZmFyaWNvbSUyMEludGVybmFsJTIwSXNzdWlu
        ZyUyMENBJTIwMDIuY3JsMIIBCQYIKwYBBQUHAQEEgfwwgfkwgckGCCsGAQUFBzAC
        hoG8bGRhcDovLy9DTj1TYWZhcmljb20lMjBJbnRlcm5hbCUyMElzc3VpbmclMjBD
        QSUyMDAyLENOPUFJQSxDTj1QdWJsaWMlMjBLZXklMjBTZXJ2aWNlcyxDTj1TZXJ2
        aWNlcyxDTj1Db25maWd1cmF0aW9uLERDPXNhZmFyaWNvbSxEQz1uZXQ/Y0FDZXJ0
        aWZpY2F0ZT9iYXNlP29iamVjdENsYXNzPWNlcnRpZmljYXRpb25BdXRob3JpdHkw
        KwYIKwYBBQUHMAGGH2h0dHA6Ly9jcmwuc2FmYXJpY29tLmNvLmtlL29jc3AwCwYD
        VR0PBAQDAgWgMD0GCSsGAQQBgjcVBwQwMC4GJisGAQQBgjcVCIfPjFaEwsQDhemF
        NoTe0Q2GoIgIZ4bBx2yDublrAgFkAgEMMB0GA1UdJQQWMBQGCCsGAQUFBwMCBggr
        BgEFBQcDATAnBgkrBgEEAYI3FQoEGjAYMAoGCCsGAQUFBwMCMAoGCCsGAQUFBwMB
        MA0GCSqGSIb3DQEBCwUAA4IBAQBMFKlncYDI06ziR0Z0/reptIJRCMo+rqo/cUuP
        KMmJCY3sXxFHs5ilNXo8YavgRLpxJxdZMkiUIVuVaBanXkz9/nMriiJJwwcMPjUV
        9nQqwNUEqrSx29L1ARFdUy7LhN4NV7mEMde3MQybCQgBjjOPcVSVZXnaZIggDYIU
        w4THLy9rDmUIasC8GDdRcVM8xDOVQD/Pt5qlx/LSbTNe2fekhTLFIGYXJVz2rcsj
        k1BfG7P3pXnsPAzu199UZnqhEF+y/0/nNpf3ftHZjfX6Ws+dQuLoDN6pIl8qmok9
        9E/EAgL1zOIzFvCRYlnjKdnsuqL1sIYFBlv3oxo6W1O+X9IZ
        -----END CERTIFICATE-----';

        // openssl_public_encrypt($initiatorPassword, $encrypted_data, $publicKey, OPENSSL_PKCS1_PADDING);

        // $securityCredential = base64_encode($encrypted_data);

        $curl_post_data = array(
            'OriginatorConversationID' => Str::uuid(),
            'InitiatorName' => env('MPESA_B2C_INITIATOR'),
            'SecurityCredential' => 'cqCvZLdCNJ8ttPepudFJ5k0n8UL0qy51y+tDYUN2plzFxABL7nKzUHmCc42eLoW+27bNPa5luRQdCv7l4lFyiTk4SpDWHUpM+NnynXj4meJI5FbtbF/Ixqqr/9YphABJE5yv5N2Bvzno9zIJAqx+8nEFZQ+/4pqZja/wj86T+FB03dRxGxjG6ztI16nr1TxU/O/9Y3MxtEJ6LaZfw/c5zCcNEY2eIEq4PPMfRFT2mCXgU8HWi/muP+GQ+LYNS4Db7EhYbQINK5m6xkArVArmsXeVf7TLVqtmc27sfmrQ4cXWhUHD7wGK9Iwna97+InE75/cTgxy2ayoe2Eb/df2FhQ==',
            'CommandID' => 'SalaryPayment',
            'Amount' => $transactionData['amount'],
            'PartyA' => env('MPESA_SHORTCODE'),
            'PartyB' => $transactionData['account'],
            'Remarks' => $transactionData['remarks'],
            'ResultURL' => env('MPESA_TEST_URL') . '/mobilemoney-payment-gateway/b2cresult',
            'QueueTimeOutURL' => env('MPESA_TEST_URL') . '/mobilemoney-payment-gateway/b2ctimeout',
            'occasion' => $transactionData['occasion']
        );

        $url = env('MPESA_ENV') == 0

        ? 'https://sandbox.safaricom.co.ke/mpesa/b2c/v3/paymentrequest'

        : 'https://api.safaricom.co.ke/mpesa/b2c/v3/paymentrequest';

        return $this->makeHttp($url, $curl_post_data);
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
