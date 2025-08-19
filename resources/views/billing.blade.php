<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @paddleJS
</head>

<div class="flex justify-center items-center h-screen">
    <x-paddle-checkout :checkout="$checkout" class="w-full" />
</div>
</html>

