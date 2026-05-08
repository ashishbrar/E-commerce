<div class="admin-page">
    <div class="admin-page-head">
        <div>
            <h2 class="admin-page-title">{{ __('Roles') }}</h2>
            <p class="admin-page-subtitle">{{ __('Manage role names, permissions, and access status.') }}</p>
        </div>
        @can('Role Add')
            <a href="{{ route('roles.create') }}" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-shield-plus"></i>
                {{ __('Create Role') }}
            </a>
        @endcan
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">{{ __('Role List') }}</h3>
        </div>
        <div class="admin-card-body">
            <div class="admin-table-wrap">
                <table id="rolesTable" class="table table-hover align-middle admin-table">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Permissions') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td class="fw-semibold">{{ $role['name'] }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach(explode(' ', $role['permissions_formatted']) as $perm)
                                            <span class="badge text-bg-success">{{ $perm }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    @if($role['status'] === 'Active')
                                        <span class="badge text-bg-success">{{ ucfirst($role['status']) }}</span>
                                    @else
                                        <span class="badge text-bg-danger">{{ ucfirst($role['status']) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="admin-actions">
                                        @can('Role Edit')
                                            <a href="{{ route('roles.edit', $role['id']) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square"></i>
                                                {{ __('Edit') }}
                                            </a>
                                        @endcan
                                        @can('Role Delete')
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                                {{ __('Delete') }}
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    {{ __('No roles found.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#rolesTable').DataTable();
    });
</script>
