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
                            <label for="phone" class="input-label">Phone:</label>
                            <input type="text" id="phone" name="phone">
                        </div>
                        <div class="input-box">
                            <label for="amount" class="input-label">Amount:</label>
                            <input type="text" id="amount" name="amount">
                        </div>
                        <button id="simulate-transaction" class="btn">Push stk</button>
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
                phone: document.getElementById('phone').value,
                amount: document.getElementById('amount').value,
            };

            xhrHttp.open('post', '/simulate-stk', true);
            xhrHttp.setRequestHeader('Content-Type', 'application/json');
            xhrHttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            xhrHttp.onreadystatechange = function(){
                if(xhrHttp.readyState === 4 && xhrHttp.status === 200){
                    let response = JSON.parse(xhrHttp.responseText);
                     if(response.ResponseCode == '0'){
                         document.getElementById('transaction-status').textContent = `${response.ResponseDescription} and ${response.CustomerMessage}`;
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
