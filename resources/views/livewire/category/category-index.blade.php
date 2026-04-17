<div class="container mt-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3">{{ __('Categories') }}</h1>
            <p class="text-muted mb-0">{{ __('Manage all your categories') }}</p>
        </div>
        @can('Category Add')
            <a href="{{ route('category.create') }}" class="btn btn-primary">
                {{ __('Create Category') }}
            </a>
        @endcan
    </div>

    <div class="table-responsive mt-4">
        <table id="categoryTable" class="table table-bordered table-hover align-middle">
            <thead class="table-light">
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
                        <td>{{ $category->name }}</td>
                        <td>
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-thumbnail" style="height: 40px; width: 40px; object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
                            @endif
                        </td>
                        <td>
                            @if(strtolower($category->status) === 'active')
                                <span class="badge bg-success">{{ ucfirst($category->status) }}</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($category->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @can('Category Edit')
                                    <a href="{{ route('category.edit', $category->id) }}" class="btn btn-sm btn-primary">
                                        {{ __('Edit') }}
                                    </a>
                                @endcan
                                @can('Category Delete')
                                    <button onclick="confirmDelete({{ $category->id }})" class="btn btn-sm btn-danger">
                                        {{ __('Delete') }}
                                    </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            {{ __('No categories found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
            confirmButtonColor: '#e3342f',
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
