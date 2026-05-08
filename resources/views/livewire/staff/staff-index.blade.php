<div class="admin-page">
    <div class="admin-page-head">
        <div>
            <h2 class="admin-page-title">{{ __('Staff') }}</h2>
            <p class="admin-page-subtitle">{{ __('Manage staff members, departments, and access status.') }}</p>
        </div>
        @can('Staff Add')
            <a href="{{ route('staff.create') }}" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-person-plus"></i>
                {{ __('Create Staff') }}
            </a>
        @endcan
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">{{ __('Staff List') }}</h3>
        </div>
        <div class="admin-card-body">
            <div class="admin-table-wrap">
                <table id="staffTable" class="table table-hover align-middle admin-table">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($staff as $member)
                            <tr>
                                <td class="fw-semibold">{{ $member->name }}</td>
                                <td class="text-muted">{{ $member->email }}</td>
                                <td>
                                    @if($member->department)
                                        <span class="badge text-bg-secondary">{{ $member->department->name }}</span>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($member->status === 'Active')
                                        <span class="badge text-bg-success">{{ ucfirst($member->status) }}</span>
                                    @else
                                        <span class="badge text-bg-danger">{{ ucfirst($member->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="admin-actions">
                                        @can('Staff Edit')
                                            <a href="{{ route('staff.edit', $member->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square"></i>
                                                {{ __('Edit') }}
                                            </a>
                                        @endcan
                                        @can('Staff Delete')
                                            <button onclick="confirmDelete({{ $member->id }}, @js($member->name))" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                                {{ __('Delete') }}
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    No staff members found.
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
        $('#staffTable').DataTable();
    });

    function confirmDelete(staffId, staffName) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete " + staffName,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('delete', staffId);
            }
        });
    }

    document.addEventListener('livewire:staffDeleted', (event) => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: event.detail.staffName + ' deleted successfully',
            showConfirmButton: false,
            timer: 3000
        });
    });
</script>
