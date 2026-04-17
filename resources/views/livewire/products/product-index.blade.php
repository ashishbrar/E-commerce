<div class="container mt-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3">{{ __('Products') }}</h1>
            <p class="text-muted mb-0">{{ __('Manage all your products') }}</p>
        </div>
        @can('Product Add')
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                {{ __('Create Products') }}
            </a>
        @endcan
    </div>

    <div class="table-responsive mt-4">
        <table id="myCustomTable" class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Image</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Description</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->price }}</td>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="height: 40px; width: 40px; object-fit: cover;">
                            @endif
                        </td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ $product->description }}</td>
                        <td>
                            @if($product->status === 'Active')
                                <span class="badge bg-success">{{ ucfirst($product->status) }}</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($product->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @can('Product Edit')
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                                        {{ __('Edit') }}
                                    </a>
                                @endcan
                                @can('Product Delete')
                                    <button onclick="confirmDelete({{ $product['id'] }})" class="btn btn-sm btn-danger">
                                        {{ __('Delete') }}
                                    </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            {{ __('No products found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#myCustomTable').DataTable();
    });

    function confirmDelete(productId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete this product.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('delete', productId);
            }
        });
    }

    document.addEventListener('livewire:userDeleted', (event) => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: event.detail + ' deleted successfully',
            showConfirmButton: false,
            timer: 3000
        });
    });
</script>
