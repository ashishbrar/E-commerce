<x-layouts.app :title="__('Dashboard')">
    <div class="container-fluid py-4">
        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card border-top border-3 border-primary shadow-sm h-100">
                    <div class="card-body">
                        <p class="card-text text-muted mb-1">Total Users</p>
                        <h4 class="card-title fw-semibold mb-0">{{ $totalUsers }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card border-top border-3 border-success shadow-sm h-100">
                    <div class="card-body">
                        <p class="card-text text-muted mb-1">Total Products</p>
                        <h4 class="card-title fw-semibold mb-0">{{ $totalProducts }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card border-top border-3 border-primary shadow-sm h-100">
                    <div class="card-body">
                        <p class="card-text text-muted mb-1">Total Services</p>
                        <h4 class="card-title fw-semibold mb-0">12</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border shadow-sm mx-auto w-100" style="max-width: 1100px;">
            <div class="card-body">
                <h4 class="card-title text-center mb-4">Users Information</h4>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
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
            </div>
        </div>
    </div>
</x-layouts.app>
