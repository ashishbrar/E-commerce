<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<x-layouts.app.sidebar :title="$title ?? null">
    <x-layouts.app.header :title="$title ?? null" />
    <main class="admin-main">
        {{ $slot }}
    </main>
</x-layouts.app.sidebar>
