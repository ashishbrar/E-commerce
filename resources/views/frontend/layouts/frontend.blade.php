<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => $title ?? 'Ace Ecommerce'])
</head>
<body class="frontend-body">
    @include('frontend.partials.header')

    <main class="frontend-shell">
        @yield('content')
    </main>

    @include('frontend.partials.footer')
    @livewireScripts
</body>
</html>
