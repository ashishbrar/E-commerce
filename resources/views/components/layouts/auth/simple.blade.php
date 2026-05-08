<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <style>
            body {
                min-height: 100vh;
                background:
                    radial-gradient(circle at top left, rgba(13, 110, 253, .14), transparent 30%),
                    linear-gradient(135deg, #f8fafc, #eef2f7);
            }

            .auth-card {
                max-width: 1040px;
                border: 1px solid rgba(148, 163, 184, .22);
                border-radius: 1.5rem;
            }

            .auth-visual {
                background:
                    linear-gradient(145deg, rgba(15, 23, 42, .96), rgba(37, 99, 235, .88)),
                    url("{{ asset('frontend/assets/banner/banner.jpg') }}") center/cover;
            }

            .auth-brand-mark {
                width: 44px;
                height: 44px;
            }
        </style>
    </head>
    <body class="bg-light text-dark antialiased">
        {{ $slot }}

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
        @include('partials.flash-toasts')
        @fluxScripts
    </body>
</html>
