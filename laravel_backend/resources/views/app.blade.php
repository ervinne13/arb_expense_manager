<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ARB Expense Manager</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"></link>    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="antialiased">
    <div>
        <div id="app"></div>
        <script src="{{ asset('js/app.js') }}"></script>
    </div>
</body>

</html>