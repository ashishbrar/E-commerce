@include('partials.head')
<style>
.sidebar {min-width: 250px; max-width: 280px; background: #fff; border-right: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0,0,0,0.02); position: fixed; top: 0; left: 0; height: 100vh; z-index: 1040; overflow-y: auto;}
.sidebar .nav-link {color: #333; border-radius: 6px; transition: background 0.15s, color 0.15s; font-weight: 500; padding: 0.65rem 1rem; margin-bottom: 2px;}
.sidebar .nav-section-title {font-size: 0.95rem; color: #6c757d; letter-spacing: 0.03em; margin-top: 1.5rem; margin-bottom: 0.5rem; text-transform: uppercase;}
.main-content-fixed {margin-left: 250px; width: calc(100% - 250px);}
@media (max-width: 991.98px) {
    .sidebar {position: static; height: auto; min-width: 100%; max-width: 100%;}
    .main-content-fixed {margin-left: 0; width: 100%;}
}
</style>
</head>
<body>
@php
    $user = auth()->user();
@endphp

<div class="d-flex flex-row min-vh-100">
    @auth
    <nav class="sidebar d-flex flex-column align-items-stretch p-0">
        <div class="d-flex justify-content-between align-items-center px-3 py-3">
            <a href="{{ route('dashboard') }}" class="navbar-brand d-flex align-items-center gap-2" wire:navigate>
                <i class="bi bi-shop" style="font-size: 1.7rem; color: #0d6efd;"></i><b>Ace - Ecommerse</b>
            </a> 
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse show" id="sidebarMenu">
            <div class="nav-section-title px-3"> 
                <i class="bi bi-grid-1x2 me-2"></i>{{ __('Platform') }}
            </div>
            <ul class="nav flex-column px-2">
                {{-- Dashboard --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}" wire:navigate>
                        <i class="bi bi-house"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>

                {{-- Users --}}
                @if($user->can('User Add') || $user->can('User Edit') || $user->can('User Delete') || $user->can('User Show'))
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 @if(request()->routeIs('users.index')) active @endif" href="{{ route('users.index') }}" wire:navigate>
                        <i class="bi bi-people"></i>
                        <span>{{ __('Users') }}</span>
                    </a>
                </li>
                @endif

                {{-- Roles --}}
                @if($user->can('Role Add') || $user->can('Role Edit') || $user->can('Role Delete') || $user->can('Role Show'))
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 @if(request()->routeIs('roles.index')) active @endif" href="{{ route('roles.index') }}" wire:navigate>
                        <i class="bi bi-link-45deg"></i>
                        <span>{{ __('Roles') }}</span>
                    </a>
                </li>
                @endif

                {{-- Products --}}
                @if($user->can('Product Add') || $user->can('Product Edit') || $user->can('Product Delete'))
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 @if(request()->routeIs('products.index')) active @endif" href="{{ route('products.index') }}" wire:navigate>
                        <i class="bi bi-box"></i>
                        <span>{{ __('Products') }}</span>
                    </a>
                </li>
                @endif

                {{-- Category --}}
                @if($user->can('Category Add') || $user->can('Category Edit') || $user->can('Category Delete'))
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 @if(request()->routeIs('category.index')) active @endif" href="{{ route('category.index') }}" wire:navigate>
                        <i class="bi bi-list"></i>
                        <span>{{ __('Category') }}</span>
                    </a>
                </li>
                @endif

                {{-- Staff --}}
                @if($user->can('Staff Add') || $user->can('Staff Edit') || $user->can('Staff Delete') || $user->can('Staff Show'))
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 @if(request()->routeIs('staff.index')) active @endif" href="{{ route('staff.index') }}" wire:navigate>
                        <i class="bi bi-person"></i>
                        <span>{{ __('Staff') }}</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </nav>
    @endauth

    <main class="flex-grow-1 bg-light main-content-fixed" style="min-height: 100vh;">
        {{ $slot }}
    </main>
</div>

@fluxScripts
</body>
</html>
