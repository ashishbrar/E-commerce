<x-layouts.app :title="__('Dashboard')">
    <div class="admin-page">
        <section class="admin-hero-panel">
            <div>
                <span class="section-badge">Overview</span>
                <h2>Monitor your ecommerce operations from one place.</h2>
                <p>Users, products, and platform access are now presented in a cleaner dashboard designed for faster management.</p>
            </div>
        </section>

        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="admin-stat-card">
                    <span>Total Users</span>
                    <h3>{{ $totalUsers }}</h3>
                    <p>Registered accounts in the platform</p>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="admin-stat-card success">
                    <span>Total Products</span>
                    <h3>{{ $totalProducts }}</h3>
                    <p>Catalog entries ready for storefront display</p>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="admin-stat-card accent">
                    <span>Responsive Panels</span>
                    <h3>All Core</h3>
                    <p>Frontend and admin layouts updated for mobile and desktop</p>
                </div>
            </div>
        </div>

        <section class="admin-table-card">
            <div class="admin-card-header">
                <div>
                    <h3>Users Information</h3>
                    <p>Quick view of team access and assigned roles.</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Roles</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-secondary">{{ $role->name }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
            </div>
        </section>
    </div>
</x-layouts.app>
