<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<x-layouts.app.sidebar :title="$title ?? null">
    <x-layouts.app.header :title="$title ?? null" dtadd="true" />
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
