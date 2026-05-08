<div class="admin-page">
    <div class="admin-page-head">
        <div>
            <h2 class="admin-page-title">{{ __('Products') }}</h2>
            <p class="admin-page-subtitle">{{ __('Manage catalog items, stock, images, and publishing status.') }}</p>
        </div>

        @can('Product Add')
            <a href="{{ route('products.create') }}" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-plus-lg"></i>
                {{ __('Create Product') }}
            </a>
        @endcan
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">{{ __('Product List') }}</h3>
            <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="paypalModeSwitch">
                <label class="form-check-label fw-semibold small" for="paypalModeSwitch">
                    Live PayPal Mode
                </label>
            </div>
        </div>

        <div class="admin-card-body">
            <div class="admin-table-wrap">
                <table id="productsTable" class="table table-hover align-middle admin-table">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Price') }}</th>
                            <th scope="col">{{ __('Image') }}</th>
                            <th scope="col">{{ __('Quantity') }}</th>
                            <th scope="col">{{ __('Description') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="fw-semibold">{{ $product->name }}</td>
                                <td>{{ $product->price }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="admin-thumb">
                                    @else
                                        <span class="text-muted small">No image</span>
                                    @endif
                                </td>
                                <td>{{ $product->quantity }}</td>
                                <td class="text-muted">{{ \Illuminate\Support\Str::limit($product->description, 70) }}</td>
                                <td>
                                    @if($product->status === 'Active')
                                        <span class="badge text-bg-success">{{ ucfirst($product->status) }}</span>
                                    @else
                                        <span class="badge text-bg-danger">{{ ucfirst($product->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="admin-actions">
                                        @can('Product Edit')
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square"></i>
                                                {{ __('Edit') }}
                                            </a>
                                        @endcan
                                        @can('Product Delete')
                                            <button onclick="confirmDelete({{ $product['id'] }})" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                                {{ __('Delete') }}
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    {{ __('No products found.') }}
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
        $('#productsTable').DataTable();
    });

    function confirmDelete(productId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete this product.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('delete', productId);
            }
        });
    }

    document.addEventListener('livewire:productDeleted', (event) => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: event.detail.productName + ' deleted successfully',
            showConfirmButton: false,
            timer: 3000
        });
    });
</script>
