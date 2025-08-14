<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @paddleJS
</head>

<x-paddle-checkout :checkout="$checkout" class="w-full">

</x-paddle-checkout>

</html>

