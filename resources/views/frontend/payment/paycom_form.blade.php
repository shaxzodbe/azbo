<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form method="POST" id="payment" action="{{ $array['url'] }}">
        <input type="hidden" name="merchant" value="{{ $array['merchant_id'] }}" autocomplete="off"/>
        <input type="hidden" name="amount" value="{{ $array['amount'] }}" autocomplete="off"/>
        <input type="hidden" name="account[order_id]" value="{{ $array['order_id'] }}" autocomplete="off"/>
    </form>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        document.getElementById('payment').submit();
    })
</script>
</body>
</html>
