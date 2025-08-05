<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">

        <!-- ✅ TAMBAHKAN BARIS INI UNTUK FAVICON ANDA -->
        <!-- Ganti 'images/telkomakses-logo.png' dengan path dan nama file favicon Anda -->
        <link rel="icon" type="image/png" href="{{ asset('images/telkom1.png') }}">
        {{-- Jika Anda punya versi .ico untuk kompatibilitas yang lebih baik, tambahkan juga: --}}
        {{-- <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}"> --}}


        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>