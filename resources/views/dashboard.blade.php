<x-layouts.app :title="__('Dashboard')">
    @php
        $recentUsers = $users->take(8);
        $adminCount = $users->filter(fn ($user) => $user->roles->contains('name', 'Admin'))->count();
        $staffCount = $users->filter(fn ($user) => $user->roles->contains('name', 'Staff'))->count();
        $viewerCount = max($totalUsers - ($adminCount + $staffCount), 0);
    @endphp

    <div class="admin-page">
        <div class="admin-page-head">
            <div>
                <h2 class="admin-page-title">Dashboard</h2>
                <p class="admin-page-subtitle">Track core platform status and manage key records quickly.</p>
            </div>
        </div>

        <div class="alert alert-primary d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-stars"></i>
            <div class="fw-semibold">Dashboard upgraded: KPI cards, quick actions, and system checklist are now active.</div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="admin-card h-100">
                    <div class="admin-card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge text-bg-primary-subtle text-primary">Accounts</span>
                            <span class="d-inline-flex align-items-center justify-content-center rounded bg-primary-subtle text-primary" style="width: 42px; height: 42px;">
                                <i class="bi bi-people-fill fs-5"></i>
                            </span>
                        </div>
                        <p class="text-muted fw-semibold mb-1">Total Users</p>
                        <h3 class="fw-bold mb-0">{{ $totalUsers }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="admin-card h-100">
                    <div class="admin-card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge text-bg-success-subtle text-success">Catalog</span>
                            <span class="d-inline-flex align-items-center justify-content-center rounded bg-success-subtle text-success" style="width: 42px; height: 42px;">
                                <i class="bi bi-box-seam fs-5"></i>
                            </span>
                        </div>
                        <p class="text-muted fw-semibold mb-1">Total Products</p>
                        <h3 class="fw-bold mb-0">{{ $totalProducts }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="admin-card h-100">
                    <div class="admin-card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge text-bg-warning-subtle text-warning">Admin Users</span>
                            <span class="d-inline-flex align-items-center justify-content-center rounded bg-warning-subtle text-warning" style="width: 42px; height: 42px;">
                                <i class="bi bi-person-gear fs-5"></i>
                            </span>
                        </div>
                        <p class="text-muted fw-semibold mb-1">With Admin Role</p>
                        <h3 class="fw-bold mb-0">{{ $adminCount }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="admin-card h-100">
                    <div class="admin-card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge text-bg-info-subtle text-info-emphasis">Staff Users</span>
                            <span class="d-inline-flex align-items-center justify-content-center rounded bg-info-subtle text-info-emphasis" style="width: 42px; height: 42px;">
                                <i class="bi bi-person-badge fs-5"></i>
                            </span>
                        </div>
                        <p class="text-muted fw-semibold mb-1">With Staff Role</p>
                        <h3 class="fw-bold mb-0">{{ $staffCount }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-xl-8">
                <div class="admin-card h-100">
                    <div class="admin-card-header">
                        <div>
                            <h3 class="admin-card-title">Recent Users</h3>
                            <p class="text-muted mb-0 small">Latest accounts and assigned roles.</p>
                        </div>
                        @if(Route::has('users.index'))
                            <a href="{{ route('users.index') }}" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-people"></i>
                                Manage Users
                            </a>
                        @endif
                    </div>
                    <div class="admin-card-body p-0">
                        <div class="admin-table-wrap">
                            <table class="table table-hover align-middle admin-table mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Roles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentUsers as $user)
                                        <tr>
                                            <td class="fw-semibold">#{{ $user->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-dark text-white small fw-bold" style="width: 34px; height: 34px;">
                                                        {{ $user->initials() }}
                                                    </span>
                                                    <span class="fw-semibold">{{ $user->name }}</span>
                                                </div>
                                            </td>
                                            <td class="text-muted">{{ $user->email }}</td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @forelse($user->roles as $role)
                                                        <span class="badge text-bg-secondary-subtle text-secondary">{{ $role->name }}</span>
                                                    @empty
                                                        <span class="text-muted small">No role</span>
                                                    @endforelse
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-5">
                                                No users found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="admin-card h-100">
                    <div class="admin-card-header">
                        <div>
                            <h3 class="admin-card-title">Quick Access</h3>
                            <p class="text-muted mb-0 small">Open core sections in one click.</p>
                        </div>
                    </div>
                    <div class="admin-card-body d-grid gap-2">
                        @if(Route::has('shop.shop'))
                            <a href="{{ route('shop.shop') }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-bag"></i>
                                View Storefront
                            </a>
                        @endif
                        @if(Route::has('products.index'))
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-box-seam"></i>
                                Manage Products
                            </a>
                        @endif
                        @if(Route::has('category.index'))
                            <a href="{{ route('category.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-tags"></i>
                                Manage Categories
                            </a>
                        @endif
                        @if(Route::has('settings.index'))
                            <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-gear"></i>
                                Open Settings
                            </a>
                        @endif
                    </div>
                    <div class="admin-card-body border-top">
                        <h4 class="h6 fw-bold mb-3">Role Mix Snapshot</h4>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Admin</span>
                            <span class="fw-semibold">{{ $adminCount }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Staff</span>
                            <span class="fw-semibold">{{ $staffCount }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Other</span>
                            <span class="fw-semibold">{{ $viewerCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h3 class="admin-card-title">System Checklist</h3>
                    <p class="text-muted mb-0 small">Quickly verify important e-commerce flows.</p>
                </div>
            </div>
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="border rounded-3 p-3 h-100">
                            <h4 class="h6 fw-bold">Authentication</h4>
                            <p class="text-muted small mb-0">Login, registration, logout, and role based access.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="border rounded-3 p-3 h-100">
                            <h4 class="h6 fw-bold">Catalog</h4>
                            <p class="text-muted small mb-0">Category/product create, edit, image upload, and listing pages.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="border rounded-3 p-3 h-100">
                            <h4 class="h6 fw-bold">Checkout</h4>
                            <p class="text-muted small mb-0">Cart, wishlist, buy now, stock guard, and payment route flow.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="border rounded-3 p-3 h-100">
                            <h4 class="h6 fw-bold">Settings</h4>
                            <p class="text-muted small mb-0">Payment credentials, SMTP settings, and app level configuration.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
