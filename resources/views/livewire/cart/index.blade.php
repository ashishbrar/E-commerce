@extends('frontend.layouts.frontend')
@section('content')

<style>
.container { margin: 4%; }
.table-img { max-height: 80px; object-fit: contain; }
</style>

<div class="container py-4">
    <h2 class="mb-4">Your Cart</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(count($cartItems) > 0)
        <form id="buyNowForm" action="{{ route('buy.now.checkout') }}" method="GET">
            @csrf
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"> Select All</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        <tr data-product-id="{{ $item->product->id }}">
                            <td><input type="checkbox" class="product-checkbox" name="product_ids[]" value="{{ $item->product->id }}" checked></td>
                            <td>{{ $item->product->name }}</td>
                            <td>
                                <img 
                                    src="{{ asset('storage/' . $item->product->image) }}"
                                    alt="{{ $item->product->name }}"
                                    class="img-thumbnail table-img"
                                    onerror="this.src='{{ asset('images/default-product.png') }}'">
                            </td>
                            <td>₹{{ number_format($item->product->price, 2) }}</td>
                            <td style="width:120px;">
                                <input 
                                    type="number"
                                    class="form-control form-control-sm cart-qty-input"
                                    data-product-id="{{ $item->product->id }}"
                                    data-price="{{ $item->product->price }}"
                                    value="{{ $item->quantity }}"
                                    min="1"
                                >
                            </td>
                            <td class="cart-item-total">₹{{ number_format($item->product->price * $item->quantity, 2) }}</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-cart-item" data-product-id="{{ $item->product->id }}">
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" class="text-end"><strong>Total:</strong></td>
                        <td colspan="2"><strong data-cart-total>₹{{ number_format($total, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('home') }}" class="btn btn-secondary">Continue Shopping</a>
                <button type="submit" class="btn btn-success px-4">Buy Now</button>
            </div>
        </form>
    @else
        <div class="text-center">Your cart is empty.</div>
    @endif
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function(){
    $('#selectAll').on('change', function() {
        $('.product-checkbox').prop('checked', this.checked);
    });
    $('.cart-qty-input').on('change', function() {
        let $input = $(this);
        let productId = $input.data('product-id');
        let price = parseFloat($input.data('price')) || 0;
        let qty = parseInt($input.val(), 10) || 1;

        if (qty < 1) qty = 1;
        $input.val(qty);
        let total = price * qty;
        $input.closest('tr').find('.cart-item-total').text('₹' + total.toFixed(2));

        $.ajax({
            url: "{{ url('cart/update') }}/" + productId,
            method: "POST",
            data: {
                quantity: qty,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                if (data.success) {
                    $('[data-cart-total]').text('₹' + parseFloat(data.cart_total).toFixed(2));
                } else if (data.error) {
                    alert(data.error);
                }
            },
        });
    });

 $('.remove-cart-item').on('click', function() {
    let productId = $(this).data('product-id');
    let $row = $(this).closest('tr');
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ url('cart/remove') }}/" + productId,
                method: "DELETE",
                data: { _token: '{{ csrf_token() }}' },
                success: function(data) {
                    if (data.success) {
                        $row.remove();
                        $('[data-cart-total]').text('₹' + parseFloat(data.cart_total).toFixed(2));

                        Swal.fire(
                            'Removed!',
                            'Your item has been removed from the cart.',
                            'success'
                        );

                        if ($('tbody tr').length <= 1) {
                            location.reload();
                        }
                    } else if (data.error) {
                        Swal.fire('Error', data.error, 'error');
                    }
                },
            });
        }
    });
});
});
</script>
@endsection
