<div class="admin-page">
    <div class="admin-page-head">
        <div>
            <h2 class="admin-page-title">{{ __('Categories') }}</h2>
            <p class="admin-page-subtitle">{{ __('Organize products with category images and status controls.') }}</p>
        </div>
        @can('Category Add')
            <a href="{{ route('category.create') }}" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-plus-lg"></i>
                {{ __('Create Category') }}
            </a>
        @endcan
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">{{ __('Category List') }}</h3>
        </div>
        <div class="admin-card-body">
            <div class="admin-table-wrap">
                <table id="categoryTable" class="table table-hover align-middle admin-table">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Image') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="fw-semibold">{{ $category->name }}</td>
                                <td>
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="admin-thumb" onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
                                    @else
                                        <span class="text-muted small">No image</span>
                                    @endif
                                </td>
                                <td>
                                    @if(strtolower($category->status) === 'active')
                                        <span class="badge text-bg-success">{{ ucfirst($category->status) }}</span>
                                    @else
                                        <span class="badge text-bg-danger">{{ ucfirst($category->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="admin-actions">
                                        @can('Category Edit')
                                            <a href="{{ route('category.edit', $category->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square"></i>
                                                {{ __('Edit') }}
                                            </a>
                                        @endcan
                                        @can('Category Delete')
                                            <button onclick="confirmDelete({{ $category->id }})" class="btn btn-sm btn-outline-danger">
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
                                    {{ __('No categories found.') }}
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
        $('#categoryTable').DataTable();
    });

    function confirmDelete(categoryId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete this category.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('delete', categoryId);
            }
        });
    }

    window.addEventListener('categoryDeleted', event => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: event.detail.categoryName + ' deleted successfully',
            showConfirmButton: false,
            timer: 3000
        });
    });
</script>
