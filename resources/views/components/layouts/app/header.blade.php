<header class="admin-header bg-white border-bottom">
    <div class="d-flex align-items-center justify-content-between gap-3 px-4 py-3">
        <div>
            <p class="text-muted small fw-semibold mb-1 text-uppercase">Admin Panel</p>
            <h1 class="h5 fw-bold mb-0">{{ $title ?? 'Dashboard' }}</h1>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm d-none d-md-inline-flex align-items-center gap-2">
                <i class="bi bi-shop"></i>
                Storefront
            </a>

            <div class="dropdown admin-user-dropdown">
                <button class="btn btn-light border dropdown-toggle d-flex align-items-center gap-2 px-2 py-1" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-dark text-white fw-bold" style="width: 36px; height: 36px;">
                        {{ auth()->user()->initials() }}
                    </span>
                    <span class="d-none d-xl-inline fw-semibold pe-1">{{ auth()->user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" aria-labelledby="userDropdown" style="min-width: 240px;">
                    <li class="px-3 py-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                                {{ auth()->user()->initials() }}
                            </span>
                            <div class="min-w-0">
                                <div class="fw-semibold text-truncate">{{ auth()->user()->name }}</div>
                                <div class="text-muted small text-truncate">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('users.edit', ['id' => auth()->user()->id]) }}">
                            <i class="bi bi-person-circle"></i>
                            {{ __('Profile') }}
                        </a>
                    </li>
                    @if(session('admin_id'))
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.switch.back') }}">
                                <i class="bi bi-arrow-left-circle"></i>
                                {{ __('Switch Back to Admin') }}
                            </a>
                        </li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger fw-semibold">
                                <i class="bi bi-box-arrow-right"></i>
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
