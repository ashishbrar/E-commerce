@php
    $flashMessages = [
        'success' => ['type' => 'success', 'icon' => 'bi-check-circle-fill'],
        'warning' => ['type' => 'warning', 'icon' => 'bi-exclamation-triangle-fill'],
        'error' => ['type' => 'danger', 'icon' => 'bi-x-circle-fill'],
        'danger' => ['type' => 'danger', 'icon' => 'bi-x-circle-fill'],
        'info' => ['type' => 'info', 'icon' => 'bi-info-circle-fill'],
        'message' => ['type' => 'success', 'icon' => 'bi-check-circle-fill'],
        'status' => ['type' => 'info', 'icon' => 'bi-info-circle-fill'],
        'smtp_success' => ['type' => 'success', 'icon' => 'bi-check-circle-fill'],
        'smtp_error' => ['type' => 'danger', 'icon' => 'bi-x-circle-fill'],
        'paypal_success' => ['type' => 'success', 'icon' => 'bi-check-circle-fill'],
    ];

    $visibleFlashMessages = [];

    foreach ($flashMessages as $key => $meta) {
        if (session()->has($key)) {
            $visibleFlashMessages[] = [
                'message' => session($key),
                'type' => $meta['type'],
                'icon' => $meta['icon'],
            ];
        }
    }

    if ($errors->any()) {
        $visibleFlashMessages[] = [
            'message' => $errors->first(),
            'type' => 'danger',
            'icon' => 'bi-x-circle-fill',
        ];
    }
@endphp

<div class="app-flash-stack" aria-live="polite" aria-atomic="true">
    @foreach($visibleFlashMessages as $flash)
        <div class="app-flash-message app-flash-{{ $flash['type'] }}" role="alert">
            <i class="bi {{ $flash['icon'] }}"></i>
            <div class="app-flash-text">{{ $flash['message'] }}</div>
            <button type="button" class="app-flash-close" aria-label="Close message">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endforeach
</div>

<style>
    .app-flash-stack {
        position: fixed;
        top: 84px;
        right: 24px;
        z-index: 5000;
        display: grid;
        gap: 12px;
        width: min(340px, calc(100vw - 32px));
        pointer-events: none;
    }

    .app-flash-message {
        position: relative;
        display: grid;
        grid-template-columns: 22px 1fr 18px;
        align-items: center;
        column-gap: 12px;
        background: #ffffff;
        color: #0f172a;
        border-radius: 10px;
        padding: 12px 14px 12px 14px;
        box-shadow: 0 12px 28px rgba(15, 23, 42, .12), 0 2px 6px rgba(15, 23, 42, .06);
        border: 1px solid rgba(15, 23, 42, .06);
        pointer-events: auto;
        font-size: 14px;
        line-height: 1.4;
        animation: appFlashSlideIn .22s ease-out;
        overflow: hidden;
    }

    .app-flash-message::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--app-flash-accent, #64748b);
    }

    .app-flash-message > i {
        font-size: 18px;
        line-height: 1;
        color: var(--app-flash-accent, #64748b);
    }

    .app-flash-text {
        font-weight: 600;
        color: #0f172a;
        word-break: break-word;
    }

    .app-flash-success { --app-flash-accent: #16a34a; }
    .app-flash-warning { --app-flash-accent: #d97706; }
    .app-flash-danger  { --app-flash-accent: #dc2626; }
    .app-flash-info    { --app-flash-accent: #2563eb; }

    .app-flash-close {
        appearance: none;
        background: transparent;
        border: 0;
        padding: 0;
        cursor: pointer;
        color: #94a3b8;
        font-size: 14px;
        line-height: 1;
        transition: color .15s ease;
    }

    .app-flash-close:hover { color: #0f172a; }

    .app-flash-message.is-hiding {
        opacity: 0;
        transform: translateX(16px);
        transition: opacity .25s ease, transform .25s ease;
    }

    @keyframes appFlashSlideIn {
        from { opacity: 0; transform: translateX(28px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    @media (max-width: 576px) {
        .app-flash-stack {
            top: 16px;
            right: 12px;
            left: 12px;
            width: auto;
        }
    }
</style>

<script>
    (function () {
        const AUTO_DISMISS_MS = 4000;

        const iconMap = {
            success: 'bi-check-circle-fill',
            warning: 'bi-exclamation-triangle-fill',
            danger:  'bi-x-circle-fill',
            error:   'bi-x-circle-fill',
            info:    'bi-info-circle-fill',
        };

        function ensureStack() {
            let stack = document.querySelector('.app-flash-stack');
            if (!stack) {
                stack = document.createElement('div');
                stack.className = 'app-flash-stack';
                stack.setAttribute('aria-live', 'polite');
                stack.setAttribute('aria-atomic', 'true');
                document.body.appendChild(stack);
            }
            return stack;
        }

        function dismiss(item) {
            if (!item || item.classList.contains('is-hiding')) return;
            item.classList.add('is-hiding');
            setTimeout(() => item.remove(), 260);
        }

        function bindItem(item) {
            const close = item.querySelector('.app-flash-close');
            if (close) close.addEventListener('click', () => dismiss(item));
            setTimeout(() => dismiss(item), AUTO_DISMISS_MS);
        }

        function renderFlash(message, type) {
            if (!message) return;
            const normalized = type === 'error' ? 'danger' : (type || 'info');
            const icon = iconMap[normalized] || iconMap.info;
            const stack = ensureStack();
            const item = document.createElement('div');
            item.className = 'app-flash-message app-flash-' + normalized;
            item.setAttribute('role', 'alert');
            item.innerHTML =
                '<i class="bi ' + icon + '"></i>' +
                '<div class="app-flash-text"></div>' +
                '<button type="button" class="app-flash-close" aria-label="Close message"><i class="bi bi-x-lg"></i></button>';
            item.querySelector('.app-flash-text').textContent = message;
            stack.appendChild(item);
            bindItem(item);
        }

        // Bind close + auto-dismiss for server-rendered messages.
        document.querySelectorAll('.app-flash-stack .app-flash-message').forEach(bindItem);

        // Livewire components without a redirect can do
        // $this->dispatch('flash', type: 'success', message: '...')
        function attachLivewireListener() {
            if (typeof window.Livewire === 'undefined') return false;
            window.Livewire.on('flash', function (payload) {
                const data = Array.isArray(payload) ? payload[0] : payload;
                if (!data) return;
                renderFlash(data.message, data.type);
            });
            return true;
        }

        if (!attachLivewireListener()) {
            document.addEventListener('livewire:init', attachLivewireListener);
        }
    })();
</script>
