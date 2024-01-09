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
                <div class="card-header">Simulate STK push</div>
                <div class="card-body">
                    <form action="#">
                        @csrf
                        <div class="input-box">
                            <label for="transactionId" class="input-label">Enter transaction ID:</label>
                            <input type="text" id="transactionId" name="transactionId">
                        </div>
                        <button id="simulate-transaction" class="btn">Show transaction status</button>
                        <p>Transation status: <span id="transaction-status"></span> </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>

        // The script for the simulation of a transation

        document.getElementById('simulate-transaction').addEventListener('click', (e) => {

            e.preventDefault();

            // Create an AJAX Request
            const xhrHttp = new XMLHttpRequest();

            const requestBody = {
                transactionId: document.getElementById('transactionId').value,
            };

            xhrHttp.open('post', '/transaction-status', true);
            xhrHttp.setRequestHeader('Content-Type', 'application/json');
            xhrHttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            xhrHttp.onreadystatechange = function(){
                if(xhrHttp.readyState === 4 && xhrHttp.status === 200){
                    let response = JSON.parse(xhrHttp.responseText);
                     if(response.ResponseCode == '0'){
                         document.getElementById('transaction-status').textContent = response.ResponseDescription;
                     } else {
                         document.getElementById('transaction-status').textContent = response.errorMessage;
                     }
                    console.log(xhrHttp.responseText);
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
