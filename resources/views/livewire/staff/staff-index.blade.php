<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold mb-1">{{ __('Staff') }}</h3>
            <div class="text-muted mb-2" style="font-size: 1.05rem;">{{ __('Manage all your staff members') }}</div>
        </div>
        @can('Staff Add')
            <a href="{{ route('staff.create') }}" class="btn btn-primary">Create Staff</a>
        @endcan
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6 col-12 d-flex align-items-center gap-2">
                    <label for="entries" class="form-label mb-0">Show</label>
                    <select id="entries" class="form-select form-select-sm" style="width: auto; display: inline-block;">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    <span class="ms-2">entries</span>
                </div>
                <div class="col-md-6 col-12 d-flex justify-content-md-end justify-content-start mt-2 mt-md-0">
                    <input type="search" class="form-control form-control-sm" placeholder="Search..." style="max-width: 220px;" id="staffSearch">
                </div>
            </div>
            <div class="table-responsive">
                <table id="myCustomTable" class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th style="min-width: 140px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($staff as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->email }}</td>
                                <td>
                                    @if($member->department)
                                        <span class="badge bg-secondary">{{ $member->department->name }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($member->status === 'Active')
                                        <span class="badge bg-success">{{ ucfirst($member->status) }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($member->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @can('Staff Edit')
                                            <a href="{{ route('staff.edit', $member->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        @endcan
                                        @can('Staff Delete')
                                            <button onclick="confirmDelete({{ $member->id }}, '{{ $member->name }}')" class="btn btn-sm btn-danger">Delete</button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No staff members found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing 1 to {{ count($staff) }} of {{ count($staff) }} entries
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><span class="page-link">Previous</span></li>
                        <li class="page-item active"><span class="page-link">1</span></li>
                        <li class="page-item disabled"><span class="page-link">Next</span></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#myCustomTable').DataTable({
            paging: false,
            info: false,
            searching: false // We'll use our own search input
        });

        $('#staffSearch').on('keyup', function() {
            $('#myCustomTable').DataTable().search(this.value).draw();
        });
    });

    function confirmDelete(staffId, staffName) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete " + staffName,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
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
