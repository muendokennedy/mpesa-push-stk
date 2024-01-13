<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mpesa payment gateway</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
</head>
<body>
    <div class="container">
        <div class="card-container">
            <div class="card">
                <div class="card-header">Obtain Access Token</div>
                <div class="card-body">
                    <button id="get-access-token" class="btn">Request Access Token</button>
                    <p>Access Token is: <span id="access-token"></span> </p>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Register URLs</div>
                <div class="card-body">
                    <button id="register-urls" class="btn">Register URLs</button>
                    <p>URL Registration was a: <span id="urls-register"></span> </p>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Simulate Transaction</div>
                <div class="card-body">
                    <form action="#">
                        @csrf
                        <div class="input-box">
                            <label for="amount" class="input-label">Amount:</label>
                            <input type="text" id="amount" name="amount">
                        </div>
                        <div class="input-box">
                            <label for="account" class="input-label">Account:</label>
                            <input type="text" id="account" name="account">
                        </div>
                        <button id="simulate-transaction" class="btn">Simulate Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('get-access-token').addEventListener('click', (e) => {
            e.preventDefault();

            // Create an AJAX Request
            const xhrHttp = new XMLHttpRequest();

            xhrHttp.open('POST', '/get-token', true);
            xhrHttp.setRequestHeader('Content-Type', 'application/json');
            xhrHttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            xhrHttp.onreadystatechange = function () {
                if(xhrHttp.readyState === 4 && xhrHttp.status === 200){
                    let response = xhrHttp.responseText;
                    document.getElementById('access-token').textContent = response;
                    console.log(response);
                } else if(xhrHttp.readyState === 4){
                    console.log('There was an error in making the request');
                }
            };

            console.log('This code was executed successfully');

            xhrHttp.send();



        });

        // The script for the register urls

        document.getElementById('register-urls').addEventListener('click', (e) => {

            e.preventDefault();

            // Create an AJAX Request
            const xhrHttp = new XMLHttpRequest();

            xhrHttp.open('post', '/register-urls', true);
            xhrHttp.setRequestHeader('Content-Type', 'application/json');
            xhrHttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            xhrHttp.onreadystatechange = function(){
                if(xhrHttp.readyState === 4 && xhrHttp.status === 200){
                    let response = JSON.parse(xhrHttp.responseText);
                    if(response.ResponseCode == '0'){
                        document.getElementById('urls-register').textContent = response.ResponseDescription;
                    } else if(response.errorMessage){
                        document.getElementById('urls-register').textContent = response.errorMessage;
                    }
                    console.log(response);
                } else {
                    console.log('There was an error while making the request');
                }
            };

            console.log('This code was executed successfully');

            xhrHttp.send();
        });
        // The script for the simulation of a transation

        document.getElementById('simulate-transaction').addEventListener('click', (e) => {

            e.preventDefault();

            // Create an AJAX Request
            const xhrHttp = new XMLHttpRequest();

            const requestBody = {
                  amount: document.getElementById('amount').value,
                  account: document.getElementById('account').value
            };

            xhrHttp.open('post', '/simulate-transaction', true);
            xhrHttp.setRequestHeader('Content-Type', 'application/json');
            xhrHttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            xhrHttp.onreadystatechange = function(){
                if(xhrHttp.readyState === 4 && xhrHttp.status === 200){
                    let response = JSON.parse(xhrHttp.responseText);
                    console.log(response);
                } else {
                    console.log('There was an error while making the request');
                }
            };

            console.log('This code was executed successfully');

            xhrHttp.send(JSON.stringify(requestBody));
        });
    </script>
</body>
</html>
