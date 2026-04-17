<div class="container mt-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3">{{ __('Users') }}</h1>
            <p class="text-muted mb-0">{{ __('Manage all your users') }}</p>
        </div>
        @can('User Add')
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                {{ __('Create User') }}
            </a>
        @endcan
    </div>

    <div class="table-responsive mt-4">
        <table id="myCustomTable" class="table table-bordered table-hover align-middle">
            <thead class="table-light">
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
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($user->roles as $role)
                                    <span class="badge bg-secondary">{{ $role->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            @if($user->status === 'Active')
                                <span class="badge bg-success">{{ ucfirst($user->status) }}</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($user->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @can('User Edit')
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm" >
                                        {{ __('Edit') }}
                                    </a>
                                @endcan
                                @if(auth()->id() !== $user->id)
                                    @can('User Delete')
                                        <button onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')" class="btn  btn-danger btn-sm">
                                            {{ __('Delete') }}
                                        </button>
                                    @endcan
                                @endif
                                @if(auth()->id() !== $user->id && auth()->user()->is_admin)
                                    <form method="POST" action="{{ route('admin.switchUser', $user->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning">
                                            {{ __('Switch') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            {{ __('No users found..') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#myCustomTable').DataTable();
    });
    function confirmDelete(userId, userName) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete " + userName,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('delete', userId);
            }
        });
    }

    document.addEventListener('livewire:userDeleted', (event) => {
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
