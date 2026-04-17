@include('partials.head')
</head>
<body class="admin-body">
@php
    $user = auth()->user();
@endphp

<div class="admin-shell">
    @auth
    <div class="admin-sidebar-backdrop d-lg-none" data-sidebar-close></div>
    <aside class="admin-sidebar">
        <div class="admin-sidebar-brand">
            <a href="{{ route('dashboard') }}" class="admin-brand" wire:navigate>
                <span class="admin-brand-mark"><i class="bi bi-grid-1x2-fill"></i></span>
                <span>
                    <strong>Ace Admin</strong>
                    <small>Commerce control room</small>
                </span>
            </a> 
            <button class="admin-sidebar-close d-lg-none" type="button" data-sidebar-close aria-label="Close navigation">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="admin-sidebar-body">
            <div class="admin-nav-label">{{ __('Platform') }}</div>
            <ul class="admin-nav-list">
                <li>
                    <a class="admin-nav-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}" wire:navigate>
                        <i class="bi bi-house-door"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
                @if($user->can('User Add') || $user->can('User Edit') || $user->can('User Delete') || $user->can('User Show'))
                    <li>
                        <a class="admin-nav-link @if(request()->routeIs('users.*')) active @endif" href="{{ route('users.index') }}" wire:navigate>
                            <i class="bi bi-people"></i>
                            <span>{{ __('Users') }}</span>
                        </a>
                    </li>
                @endif
                @if($user->can('Role Add') || $user->can('Role Edit') || $user->can('Role Delete') || $user->can('Role Show'))
                    <li>
                        <a class="admin-nav-link @if(request()->routeIs('roles.*')) active @endif" href="{{ route('roles.index') }}" wire:navigate>
                            <i class="bi bi-shield-lock"></i>
                            <span>{{ __('Roles') }}</span>
                        </a>
                    </li>
                @endif
                @if($user->can('Product Add') || $user->can('Product Edit') || $user->can('Product Delete'))
                    <li>
                        <a class="admin-nav-link @if(request()->routeIs('products.*')) active @endif" href="{{ route('products.index') }}" wire:navigate>
                            <i class="bi bi-box-seam"></i>
                            <span>{{ __('Products') }}</span>
                        </a>
                    </li>
                @endif
                @if($user->can('Category Add') || $user->can('Category Edit') || $user->can('Category Delete'))
                    <li>
                        <a class="admin-nav-link @if(request()->routeIs('category.*')) active @endif" href="{{ route('category.index') }}" wire:navigate>
                            <i class="bi bi-tags"></i>
                            <span>{{ __('Category') }}</span>
                        </a>
                    </li>
                @endif
                @if($user->can('Staff Add') || $user->can('Staff Edit') || $user->can('Staff Delete') || $user->can('Staff Show'))
                    <li>
                        <a class="admin-nav-link @if(request()->routeIs('staff.*')) active @endif" href="{{ route('staff.index') }}" wire:navigate>
                            <i class="bi bi-person-badge"></i>
                            <span>{{ __('Staff') }}</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </aside>
    @endauth

    <div class="admin-content">
        {{ $slot }}
    </div>
</div>

@fluxScripts
@livewireScripts
</body>
</html>
