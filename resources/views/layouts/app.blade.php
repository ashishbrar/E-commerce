      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
              <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

      
   <x-layouts.app.sidebar :title="$title ?? null">
        <x-layouts.app.header :title="$title ?? null" dtadd="true" />
        <flux:main>
            {{ $slot }}
        </flux:main>
   </x-layouts.app.sidebar>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
