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
            'ConfirmationURL' => env('MPESA_TEST_URL') .'/mobilemoney-payment-gateway/confirmation',
            'ValidationURL' => env('MPESA_TEST_URL').'/mobilemoney-payment-gateway/validation'
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

    public function stkPush(Request $request)
    {
        $data = $request->getContent();

        $transactionData = json_decode($data, true);

        $timestamp = date('YmdHis');

        $password = base64_encode(env('MPESA_STK_SHORTCODE') . env('MPESA_PASSKEY') . $timestamp);

        $curl_post_data = array(
            'BusinessShortCode' => env('MPESA_STK_SHORTCODE'),
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => "CustomerPayBillOnline",
            'Amount' => $transactionData['amount'],
            'PartyA' => $transactionData['phone'],
            'PartyB' => env('MPESA_STK_SHORTCODE'),
            'PhoneNumber' => $transactionData['phone'],
            'CallBackURL' => env('MPESA_TEST_URL') . '/mobilemoney-payment-gateway/stk',
            'AccountReference' => env('MPESA_B2C_INITIATOR'),
            'TransactionDesc' => "Payment of purchased prouducts"
        );

        $url = env('MPESA_ENV') == 0

        ? 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'

        : 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        return $this->makeHttp($url, $curl_post_data);

    }

    public function checkTransactionStatus(Request $request)
    {
        $data = $request->getContent();

        $transactionData = json_decode($data, true);

        $curl_post_data = array(

            'Initiator' => env('MPESA_B2C_INITIATOR'),
            'SecurityCredential' =>  'EZXoozsI0SxboGYBkj/WhcFpBRA4TwuXD3truFVfLRg6JnKzE+fmJA2n6ujbkaGTeWEzyr+6GIhom5wRt/H/zWH1NvmdWwdXekh18epOJrPhw6dtF8D0LoIubjX6O4485w/1hiidQ6d91KFeFe9FEWJChNgPdKpohfFK/LqC9bcTV40hwh5xGGXFt0+av9ubvfLh2s8fytFpaCY1lyi1lk+YAtLaGxATouzkkOPxX+teGE3cavoLXE2G7qKQvPN7+crYpnVDt2TCPvoR8xUreZl3f12DQn0F249ieM2f5TaFK+UArzpWakfjK4CGUSNdNbuOG6Sr4tylaiSHxqVjhw==',
            'CommandID' => 'TransactionStatusQuery',
            'TransactionID' => $transactionData['transactionId'],
            'PartyA' => env('MPESA_STK_SHORTCODE'),
            'IdentifierType' => 4,
            'ResultURL' => env('MPESA_TEST_URL') . '/mobilemoney-payment-gateway/TransactionStatus/result/',
            'QueueTimeOutURL' =>env('MPESA_TEST_URL') . '/mobilemoney-payment-gateway/TransactionStatus/queue/',
            'Remarks' => 'Check Transaction status',
            'Occassion' =>  'Verify Transaaction',

        );


        $url = env('MPESA_ENV') == 0

        ? 'https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query'

        : 'https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query';

        return $this->makeHttp($url, $curl_post_data);
    }

    public function reverseTransaction(Request $request)
    {
        $data = $request->getContent();

        $transactionData = json_decode($data, true);

        $curl_post_data = array(
            'Initiator' => env('MPESA_B2C_INITIATOR'),
            'SecurityCredential' => 'FMoJTf+oz3pml/qxZx3jKeozCkw74+m95aP7M/Q8TR2ii9NB9DmBKQ3lUn7Q7idHdbTKV2AM1PJqB4+uUXTgF8+q3JNiVM0aAqTRXrRi6zptkyamfB4llQBjQ6vroz8lgSPggE73lvvOwfd4zwS35c3PhZTdwHhZzQecq4Dwf/WMxF4SyQZjylBd5M7kox/eScnQ5xe/zfZ8sqbDF0d/oiTH0YhwXV8Nh20G8pn3dyvGeW238tvLnZqhiHOBEvqYtYHLM2UR3yV2VBYgVarjgmM3cAI5ORn0KNV4j4AtGO/MmRp/xKVNLJnw0woT9x9BLqPKi+vc+3rjWwwW/JC41Q==',
            'CommandID' => "TransactionReversal",
            'TransactionID' => $transactionData['transactionId'],
            'Amount' => $transactionData['amount'],
            'ReceiverParty' => env('MPESA_STK_SHORTCODE'),
            'ReceiverIdentifierType' => 11,
            'ResultURL' => env('MPESA_TEST_URL') . '/mobilemoney-payment-gateway/Reversal/result',
            'QueueTimeOutURL' => env('MPESA_TEST_URL') . '/mobilemoney-payment-gateway/Reversal/queue',
            'Remarks' => 'ReversalRequest',
            'Occassion' => 'ErronousTransaction',
        );

        $url = env('MPESA_ENV') == 0

        ? 'https://sandbox.safaricom.co.ke/mpesa/reversal/v1/request'

        : 'https://api.safaricom.co.ke/mpesa/reversal/v1/request';

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
    public function pay()
    {
        return view('pay');
    }
    public function query()
    {
        return view('query');
    }
}
