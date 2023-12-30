<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="/v1/stkpush" method="POST">
        @method('POST')
        @csrf
        <button type="submit">Make payment</button>
    </form>
</body>
</html>
