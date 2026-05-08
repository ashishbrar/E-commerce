<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            :root {
                --admin-bg: #f4f6f8;
                --admin-panel: #ffffff;
                --admin-border: #e5e7eb;
                --admin-muted: #6b7280;
                --admin-text: #111827;
                --admin-primary: #2563eb;
                --admin-primary-dark: #1d4ed8;
                --admin-sidebar: #101827;
            }

            body {
                background: var(--admin-bg);
                color: var(--admin-text);
                font-size: 15px;
            }

            .admin-shell {
                min-height: 100vh;
            }

            .admin-sidebar {
                width: 278px;
                position: fixed;
                inset: 0 auto 0 0;
                z-index: 1040;
                overflow-y: auto;
                background: var(--admin-sidebar);
                color: rgba(255, 255, 255, .72);
                box-shadow: 10px 0 30px rgba(15, 23, 42, .08);
            }

            .admin-brand {
                display: flex;
                align-items: center;
                gap: 12px;
                color: #fff;
                text-decoration: none;
                font-size: 17px;
                font-weight: 800;
            }

            .admin-brand-icon {
                width: 40px;
                height: 40px;
                display: grid;
                place-items: center;
                border-radius: 10px;
                background: var(--admin-primary);
            }

            .admin-sidebar .nav-section-title {
                margin: 22px 16px 10px;
                color: rgba(255, 255, 255, .42);
                font-size: 12px;
                font-weight: 800;
                letter-spacing: .12em;
                text-transform: uppercase;
            }

            .admin-sidebar .nav-link {
                display: flex;
                align-items: center;
                gap: 12px;
                margin: 4px 12px;
                border-radius: 8px;
                color: rgba(255, 255, 255, .76);
                font-weight: 650;
                padding: 11px 14px;
            }

            .admin-sidebar .nav-link i {
                width: 22px;
                color: rgba(255, 255, 255, .52);
                font-size: 17px;
            }

            .admin-sidebar .nav-link:hover,
            .admin-sidebar .nav-link.active {
                background: rgba(255, 255, 255, .1);
                color: #fff;
            }

            .admin-sidebar .nav-link:hover i,
            .admin-sidebar .nav-link.active i {
                color: #60a5fa;
            }

            .admin-submenu-toggle {
                width: calc(100% - 24px);
                background: transparent;
                border: 0;
                text-align: left;
                font: inherit;
                cursor: pointer;
            }

            .admin-submenu-caret {
                transition: transform .2s ease;
            }

            .admin-submenu-toggle.is-open .admin-submenu-caret {
                transform: rotate(180deg);
            }

            .admin-submenu {
                margin: 0 12px;
                padding-left: 10px;
                border-left: 1px dashed rgba(255, 255, 255, .18);
                max-height: 0;
                opacity: 0;
                overflow: hidden;
                transition: max-height .25s ease, opacity .2s ease, margin .2s ease;
            }

            .admin-submenu.is-open {
                max-height: 480px;
                opacity: 1;
                margin: 4px 12px 10px;
            }

            .admin-submenu .nav-link {
                margin: 2px 0;
                padding: 8px 10px;
                font-size: 13px;
                border-radius: 6px;
            }

            .admin-submenu .nav-link i {
                width: 16px;
                font-size: 13px;
            }

            .admin-main {
                min-height: 100vh;
                margin-left: 278px;
                background: var(--admin-bg);
            }

            .admin-page-content {
                padding: 24px;
                width: 100%;
                max-width: 1440px;
                margin: 0 auto;
            }

            .admin-mobile-toggle {
                display: none;
            }

            .admin-header {
                position: sticky;
                top: 0;
                z-index: 1020;
            }

            .admin-page {
                width: 100%;
            }

            .admin-page-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                margin-bottom: 20px;
            }

            .admin-page-title {
                margin: 0;
                font-size: clamp(1.35rem, 1.2rem + .45vw, 1.85rem);
                font-weight: 800;
                letter-spacing: 0;
            }

            .admin-page-subtitle {
                color: var(--admin-muted);
                margin: 4px 0 0;
                font-weight: 500;
            }

            .admin-card {
                background: var(--admin-panel);
                border: 1px solid var(--admin-border);
                border-radius: 8px;
                box-shadow: 0 16px 36px rgba(17, 24, 39, .06);
            }

            .admin-card-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                padding: 18px 20px;
                border-bottom: 1px solid var(--admin-border);
            }

            .admin-card-title {
                font-size: 1rem;
                font-weight: 800;
                margin: 0;
            }

            .admin-card-body {
                padding: 20px;
            }

            .admin-form-actions {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
                padding-top: 16px;
                margin-top: 4px;
                border-top: 1px solid var(--admin-border);
            }

            .admin-table-wrap {
                width: 100%;
                overflow-x: auto;
            }

            .admin-table {
                min-width: 760px;
                margin-bottom: 0;
            }

            .admin-table thead th {
                background: #f9fafb;
                color: #475569;
                font-size: .76rem;
                text-transform: uppercase;
                font-weight: 800;
                border-bottom: 1px solid var(--admin-border);
                white-space: nowrap;
            }

            .admin-table td {
                vertical-align: middle;
            }

            .admin-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            .btn {
                border-radius: 7px;
                font-weight: 650;
            }

            .btn-primary {
                background: var(--admin-primary);
                border-color: var(--admin-primary);
            }

            .btn-primary:hover {
                background: var(--admin-primary-dark);
                border-color: var(--admin-primary-dark);
            }

            .form-label {
                color: #374151;
                font-weight: 700;
                margin-bottom: 6px;
            }

            .form-control,
            .form-select {
                border-color: #d8dee7;
                border-radius: 7px;
                min-height: 42px;
            }

            textarea.form-control {
                min-height: 92px;
            }

            .form-control:focus,
            .form-select:focus {
                border-color: var(--admin-primary);
                box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .14);
            }

            .admin-thumb {
                width: 46px;
                height: 46px;
                object-fit: cover;
                border-radius: 8px;
                border: 1px solid var(--admin-border);
                background: #fff;
            }

            .admin-preview {
                max-width: 180px;
                max-height: 180px;
                object-fit: cover;
                border-radius: 8px;
                border: 1px solid var(--admin-border);
            }

            .admin-gallery-thumb {
                width: 82px;
                height: 82px;
                object-fit: cover;
                border-radius: 8px;
                border: 1px solid var(--admin-border);
            }

            .badge {
                border-radius: 999px;
                font-weight: 700;
                padding: .42em .68em;
            }

            .dataTables_wrapper .dataTables_filter input,
            .dataTables_wrapper .dataTables_length select {
                border: 1px solid #d8dee7;
                border-radius: 7px;
                padding: 6px 10px;
                margin-left: 8px;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                border-radius: 7px !important;
                border: 1px solid var(--admin-border) !important;
                margin-left: 4px;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                background: var(--admin-primary) !important;
                border-color: var(--admin-primary) !important;
                color: #fff !important;
            }

            @media (max-width: 991.98px) {
                .admin-sidebar {
                    position: static;
                    width: 100%;
                    min-height: auto;
                }

                .admin-main {
                    margin-left: 0;
                }

                .admin-mobile-toggle {
                    display: inline-flex;
                }
            }

            @media (min-width: 992px) {
                .admin-sidebar-menu.collapse {
                    display: block !important;
                    height: auto !important;
                    visibility: visible !important;
                }
            }

            @media (max-width: 767.98px) {
                .admin-page-content {
                    padding: 16px;
                }

                .admin-header .px-4 {
                    padding-left: 16px !important;
                    padding-right: 16px !important;
                }

                .admin-page-head,
                .admin-card-header {
                    align-items: stretch;
                    flex-direction: column;
                }

                .admin-page-head .btn,
                .admin-card-header .btn,
                .admin-form-actions .btn {
                    width: 100%;
                }

                .admin-form-actions {
                    flex-direction: column-reverse;
                }

                .dataTables_wrapper .dataTables_length,
                .dataTables_wrapper .dataTables_filter,
                .dataTables_wrapper .dataTables_info,
                .dataTables_wrapper .dataTables_paginate {
                    float: none;
                    text-align: left;
                    margin-top: 10px;
                }

                .dataTables_wrapper .dataTables_filter input {
                    width: 100%;
                    margin: 6px 0 0;
                }
            }
        </style>
    </head>
    <body>
        @php
            $adminUser = auth()->user();
            $showFullAdminMenu = $adminUser && ($adminUser->is_admin || $adminUser->role === 'Admin' || $adminUser->hasRole('Admin'));
        @endphp
        <div class="admin-shell">
            <aside class="admin-sidebar">
                <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-white border-opacity-10">
                    <a href="{{ route('dashboard') }}" class="admin-brand" wire:navigate>
                        <span class="admin-brand-icon"><i class="bi bi-shop"></i></span>
                        <span>Ace Admin</span>
                    </a>
                    <button class="btn btn-sm btn-outline-light admin-mobile-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
                        <i class="bi bi-list"></i>
                    </button>
                </div>

                <div class="admin-sidebar-menu collapse show" id="sidebarMenu">
                    <div class="nav-section-title">Platform</div>
                    <ul class="nav flex-column pb-4">
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}" wire:navigate>
                                <i class="bi bi-speedometer2"></i>
                                <span>{{ __('Dashboard') }}</span>
                            </a>
                        </li>

                        @if($showFullAdminMenu || ($adminUser && ($adminUser->can('User Add') || $adminUser->can('User Edit') || $adminUser->can('User Delete') || $adminUser->can('User Show'))))
                            <li class="nav-item">
                                <a class="nav-link @if(request()->routeIs('users.*')) active @endif" href="{{ route('users.index') }}" wire:navigate>
                                    <i class="bi bi-people"></i>
                                    <span>{{ __('Users') }}</span>
                                </a>
                            </li>
                        @endif

                        @if($showFullAdminMenu || ($adminUser && ($adminUser->can('Role Add') || $adminUser->can('Role Edit') || $adminUser->can('Role Delete') || $adminUser->can('Role Show'))))
                            <li class="nav-item">
                                <a class="nav-link @if(request()->routeIs('roles.*')) active @endif" href="{{ route('roles.index') }}" wire:navigate>
                                    <i class="bi bi-shield-lock"></i>
                                    <span>{{ __('Roles') }}</span>
                                </a>
                            </li>
                        @endif

                        @if($showFullAdminMenu || ($adminUser && ($adminUser->can('Product Add') || $adminUser->can('Product Edit') || $adminUser->can('Product Delete') || $adminUser->can('Product Show'))))
                            <li class="nav-item">
                                <a class="nav-link @if(request()->routeIs('products.*')) active @endif" href="{{ route('products.index') }}" wire:navigate>
                                    <i class="bi bi-box-seam"></i>
                                    <span>{{ __('Products') }}</span>
                                </a>
                            </li>
                        @endif

                        @if($showFullAdminMenu || ($adminUser && ($adminUser->can('Category Add') || $adminUser->can('Category Edit') || $adminUser->can('Category Delete') || $adminUser->can('Category Show'))))
                            <li class="nav-item">
                                <a class="nav-link @if(request()->routeIs('category.*')) active @endif" href="{{ route('category.index') }}" wire:navigate>
                                    <i class="bi bi-tags"></i>
                                    <span>{{ __('Category') }}</span>
                                </a>
                            </li>
                        @endif

                        @if($showFullAdminMenu || ($adminUser && ($adminUser->can('Staff Add') || $adminUser->can('Staff Edit') || $adminUser->can('Staff Delete') || $adminUser->can('Staff Show'))))
                            <li class="nav-item">
                                <a class="nav-link @if(request()->routeIs('staff.*')) active @endif" href="{{ route('staff.index') }}" wire:navigate>
                                    <i class="bi bi-person-badge"></i>
                                    <span>{{ __('Staff') }}</span>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <button type="button"
                                    class="nav-link admin-submenu-toggle @if(request()->routeIs('settings.*')) active is-open @endif"
                                    data-submenu-toggle="settingsSubmenu"
                                    aria-expanded="{{ request()->routeIs('settings.*') ? 'true' : 'false' }}"
                                    aria-controls="settingsSubmenu">
                                <i class="bi bi-gear"></i>
                                <span>{{ __('Settings') }}</span>
                                <i class="bi bi-chevron-down ms-auto admin-submenu-caret"></i>
                            </button>
                            <div class="admin-submenu @if(request()->routeIs('settings.*')) is-open @endif" id="settingsSubmenu">
                                <a class="nav-link @if(request()->routeIs('settings.index')) active @endif" href="{{ route('settings.index') }}" wire:navigate>
                                    <i class="bi bi-sliders2"></i>
                                    <span>{{ __('General') }}</span>
                                </a>
                                <a class="nav-link @if(request()->routeIs('settings.email-templates')) active @endif" href="{{ route('settings.email-templates') }}" wire:navigate>
                                    <i class="bi bi-envelope-paper"></i>
                                    <span>{{ __('Email Templates') }}</span>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </aside>

            <main class="admin-main">
                {{ $slot }}
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            (function () {
                function toggleSubmenu(button) {
                    const id = button.getAttribute('data-submenu-toggle');
                    const target = document.getElementById(id);
                    if (!target) return;

                    const willOpen = !target.classList.contains('is-open');
                    target.classList.toggle('is-open', willOpen);
                    button.classList.toggle('is-open', willOpen);
                    button.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                }

                function bindSidebar() {
                    document.querySelectorAll('[data-submenu-toggle]').forEach(function (btn) {
                        if (btn.dataset.submenuBound === '1') return;
                        btn.dataset.submenuBound = '1';
                        btn.addEventListener('click', function (event) {
                            event.preventDefault();
                            toggleSubmenu(btn);
                        });
                    });

                    // Reset bootstrap user dropdown after wire:navigate.
                    document.querySelectorAll('.admin-user-dropdown .dropdown-toggle').forEach(function (toggle) {
                        toggle.setAttribute('aria-expanded', 'false');
                    });
                }

                document.addEventListener('DOMContentLoaded', bindSidebar);
                document.addEventListener('livewire:navigated', bindSidebar);
            })();
        </script>
        @include('partials.flash-toasts')
        @fluxScripts
    </body>
</html>
