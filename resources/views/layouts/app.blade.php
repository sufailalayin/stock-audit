<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
         <link rel="manifest" href="/manifest.json">
         <meta name="theme-color" content="#0d6efd">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
/* ===== STOCK AUDIT STATUS COLORS ===== */

/* ROW COLORS */
tr.status-matched {
    background-color: #e8f7ef !important; /* light green */
}

tr.status-mismatch {
    background-color: #fdeaea !important; /* light red */
}

tr.status-pending {
    background-color: #fff1f4 !important; /* rose */
}

/* STATUS BADGES */
.badge-matched {
    background-color: #28a745;
    color: #fff;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
}

.badge-mismatch {
    background-color: #dc3545;
    color: #fff;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
}

.badge-pending {
    background-color: #e83e8c;
    color: #fff;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
}
</style>

    </head>
    <script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js');
}
</script>

    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
