<div class="container mt-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3">{{ __('Roles') }}</h1>
            <p class="text-muted mb-0">{{ __('Manage all your users roles') }}</p>
        </div>
        @can('Role Add')
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                {{ __('Create Role') }}
            </a>
        @endcan
    </div>

    <div class="table-responsive mt-4">
        <table id="rolesTable" class="table table-bordered table-hover align-middle">
            <thead class="table-light">
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
                        <td>{{ $role['name'] }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(explode(' ', $role['permissions_formatted']) as $perm)
                                    <span class="badge bg-success">{{ $perm }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            @if($role['status'] === 'Active')
                                <span class="badge bg-success">{{ ucfirst($role['status']) }}</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($role['status']) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @can('Role Edit')
                                    <a href="{{ route('roles.edit', $role['id']) }}" class="btn btn-sm btn-primary">
                                        {{ __('Edit') }}
                                    </a>
                                @endcan
                                @can('Role Delete')
                                    <button class="btn btn-sm btn-danger">
                                        {{ __('Delete') }}
                                    </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            {{ __('No roles found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#rolesTable').DataTable();
    });
</script>
