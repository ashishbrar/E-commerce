<?php

declare(strict_types=1);

use Flasher\Prime\Configuration;

return Configuration::from([
    'default' => 'flasher',
    'main_script' => '/vendor/flasher/flasher.min.js',
    'styles' => [
        '/vendor/flasher/flasher.min.css',
    ],
    'inject_assets' => false,
    'translate' => true,
    'excluded_paths' => [],

    // Disable auto-conversion of Laravel session flashes — the custom
    // partials/flash-toasts.blade.php partial handles rendering.
    // Must be `false` (not []); the package merges defaults otherwise.
    'flash_bag' => false,
]);
