<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Rapor SD Kecamatan Wagir') }}</title>

    <link rel="icon" href="/img/tutwuri.png" type="image/png" />
    <meta name="description" content="{{$meta['description'] ?? 'Website Resmi Desa Samar' }}">
    <!-- Opengraph meta -->
    <meta property="og:url" content="{{ $meta['url'] ?? 'https://raporsd.alsya.my.id' }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $meta['title'] ?? 'PKG Kecamatan Wagir' }}">
    <meta property="og:description" content="{{ $meta['description'] ?? 'Website Resmi PKG Kecamatan Wagir' }}">
    <meta property="og:image" content="{{ $meta['image'] ?? '/img/tutwuri.png' }}">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="raporsd.alsya.my.id">
    <meta property="twitter:url" content="https://raporsd.alsya.my.id">
    <meta name="twitter:title" content="">
    <meta name="twitter:description" content="">
    <meta name="twitter:image" content="{{ $meta['image'] ?? '/img/tutwuri.png' }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js'])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>