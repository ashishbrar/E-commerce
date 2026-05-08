<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Ace Ecommerce' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --ace-ink: #111827;
            --ace-muted: #64748b;
            --ace-line: #e5e7eb;
            --ace-surface: #ffffff;
            --ace-soft: #f6f8fb;
            --ace-primary: #0f172a;
            --ace-accent: #2563eb;
        }

        body {
            min-height: 100vh;
            background: var(--ace-soft);
            color: var(--ace-ink);
            font-family: Inter, Instrument Sans, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            letter-spacing: 0;
        }

        a {
            transition: color .18s ease, background-color .18s ease, border-color .18s ease, transform .18s ease;
        }

        .btn {
            border-radius: 10px;
            font-weight: 700;
        }

        .btn-dark,
        .btn-primary {
            background: var(--ace-primary);
            border-color: var(--ace-primary);
        }

        .btn-dark:hover,
        .btn-primary:hover {
            background: var(--ace-accent);
            border-color: var(--ace-accent);
        }

        .section-kicker {
            color: var(--ace-accent);
            font-size: 12px;
            font-weight: 850;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    @include('frontend.partials.header')

    <main>
        @yield('content')
    </main>

    @include('frontend.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.flash-toasts')
</body>
</html>
