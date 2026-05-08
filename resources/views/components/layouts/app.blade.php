<x-layouts.app.sidebar :title="$title ?? null">
    <x-layouts.app.header :title="$title ?? null" />
    <div class="admin-page-content">
        {{ $slot }}
    </div>
</x-layouts.app.sidebar>
