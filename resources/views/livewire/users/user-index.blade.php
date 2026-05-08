<div class="admin-page">
    <div class="admin-page-head">
        <div>
            <h2 class="admin-page-title">{{ __('Users') }}</h2>
            <p class="admin-page-subtitle">{{ __('Manage user accounts, roles, and account status.') }}</p>
        </div>
        @can('User Add')
            <a href="{{ route('users.create') }}" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-person-plus"></i>
                {{ __('Create User') }}
            </a>
        @endcan
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">{{ __('User List') }}</h3>
        </div>
        <div class="admin-card-body">
            <div class="admin-table-wrap">
                <table id="usersTable" class="table table-hover align-middle admin-table">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Email') }}</th>
                            <th scope="col">{{ __('Roles') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td class="text-muted">{{ $user->email }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($user->roles as $role)
                                            <span class="badge text-bg-secondary">{{ $role->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    @if($user->status === 'Active')
                                        <span class="badge text-bg-success">{{ ucfirst($user->status) }}</span>
                                    @else
                                        <span class="badge text-bg-danger">{{ ucfirst($user->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="admin-actions">
                                        @can('User Edit')
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square"></i>
                                                {{ __('Edit') }}
                                            </a>
                                        @endcan
                                        @if(auth()->id() !== $user->id)
                                            @can('User Delete')
                                                <button onclick="confirmDelete({{ $user->id }}, @js($user->name))" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                    {{ __('Delete') }}
                                                </button>
                                            @endcan
                                        @endif
                                        @if(auth()->id() !== $user->id && auth()->user()->is_admin)
                                            <form method="POST" action="{{ route('admin.switchUser', $user->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-arrow-left-right"></i>
                                                    {{ __('Switch') }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    {{ __('No users found.') }}
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
    $(document).ready(function() {
        $('#usersTable').DataTable();
    });

    function confirmDelete(userId, userName) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete " + userName,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('delete', userId);
            }
        });
    }

    document.addEventListener('userDeleted', (event) => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: event.detail.userName + ' deleted successfully',
            showConfirmButton: false,
            timer: 3000
        });
    });
</script>
