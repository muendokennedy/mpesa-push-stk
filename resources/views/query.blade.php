<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form aaction="/v2/query/transaction" method="POST">
        @method('POST')
        @csrf
        <button type="submit">Query the transction</button>
    </form>
</body>
</html>
