@auth
<header class="admin-topbar">
    <div class="admin-topbar-inner">
        <div class="admin-topbar-left">
            <button class="admin-hamburger d-lg-none" type="button" data-sidebar-toggle aria-label="Open navigation">
                <i class="bi bi-list"></i>
            </button>
            <div>
                <span class="admin-page-eyebrow">Admin Workspace</span>
                <h1 class="admin-page-title">{{ $title ?? 'Dashboard' }}</h1>
            </div>
        </div>
        <div class="ms-auto">
            <div class="dropdown">
                <button class="admin-user-toggle dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="admin-avatar">
                        {{ auth()->user()->initials() }}
                    </span>
                    <span class="admin-user-copy d-none d-md-flex">
                        <strong>{{ auth()->user()->name }}</strong>
                        <small>{{ auth()->user()->email }}</small>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow admin-user-menu" aria-labelledby="userDropdown">
                    <li class="px-3 py-2">
                        <div class="d-flex align-items-center">
                            <span class="admin-avatar me-2">
                                {{ auth()->user()->initials() }}
                            </span>
                            <div>
                                <div class="fw-semibold text-truncate">{{ auth()->user()->name }}</div>
                                <div class="text-muted small text-truncate">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.switch.back') }}">
                            <i class="bi bi-person me-2"></i> {{ __('Switch Back to Admin') }}
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('users.edit', ['id' => auth()->user()->id]) }}">
                            <i class="bi bi-person-circle me-2"></i> {{ __('Profile') }}
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center">
                                <i class="bi bi-box-arrow-right me-2"></i> {{ __('Log Out') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
@endauth
