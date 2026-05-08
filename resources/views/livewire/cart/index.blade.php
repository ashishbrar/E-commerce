@extends('frontend.layouts.frontend')
@section('content')

<style>
    .cart-page {
        background: #f6f8fb;
        min-height: 70vh;
    }

    .cart-panel {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, .06);
    }

    .cart-product-img {
        width: 74px;
        height: 74px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #fff;
    }

    .cart-table {
        min-width: 820px;
    }

    .cart-table th {
        color: #475569;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        background: #f8fafc;
        white-space: nowrap;
    }

    .cart-summary {
        position: sticky;
        top: 92px;
    }

    .cart-qty-control {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .cart-qty-control .btn {
        width: 30px;
        height: 30px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .cart-qty-control .form-control {
        text-align: center;
        min-width: 56px;
    }

    .cart-empty {
        max-width: 520px;
        margin: 0 auto;
    }

    @media (max-width: 991.98px) {
        .cart-summary {
            position: static;
        }
    }
</style>

<div class="cart-page py-5">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <p class="section-kicker mb-2">Shopping Cart</p>
                <h1 class="fw-bold mb-1">Your Cart</h1>
                <p class="text-muted mb-0">Review products, update quantities, and checkout securely.</p>
            </div>
            <a href="{{ route('home') }}" class="btn btn-outline-dark d-inline-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i>
                Continue Shopping
            </a>
        </div>

        @foreach(['success' => 'success', 'warning' => 'warning', 'error' => 'danger', 'info' => 'info'] as $key => $type)
            @if(session($key))
                <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                    {{ session($key) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        @endforeach

        @if(count($cartItems) > 0)
            <form id="buyNowForm" action="{{ route('buy.now.checkout') }}" method="GET">
                @csrf
                <div class="row g-4 align-items-start">
                    <div class="col-12 col-xl-8">
                        <div class="cart-panel overflow-hidden">
                            <div class="d-flex align-items-center justify-content-between gap-3 p-3 p-md-4 border-bottom">
                                <div>
                                    <h2 class="h5 fw-bold mb-1">Cart Items</h2>
                                    <p class="text-muted small mb-0">{{ count($cartItems) }} item{{ count($cartItems) === 1 ? '' : 's' }} in your cart.</p>
                                </div>
                                <div class="form-check mb-0">
                                    <input type="checkbox" id="selectAll" class="form-check-input" checked>
                                    <label for="selectAll" class="form-check-label fw-semibold">Select all</label>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle cart-table mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 52px;"></th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th style="width: 130px;">Quantity</th>
                                            <th>Total</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cartRows">
                                        @foreach($cartItems as $item)
                                            @if($item->product)
                                                <tr data-product-id="{{ $item->product->id }}">
                                                    <td>
                                                        <input type="checkbox" class="form-check-input product-checkbox" name="product_ids[]" value="{{ $item->product->id }}" checked>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <img
                                                                src="{{ asset('storage/' . $item->product->image) }}"
                                                                alt="{{ $item->product->name }}"
                                                                class="cart-product-img"
                                                                onerror="this.src='{{ asset('images/default-product.png') }}'">
                                                            <div>
                                                                <div class="fw-bold">{{ $item->product->name }}</div>
                                                                <div class="text-muted small">Available: {{ $item->product->quantity }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="fw-semibold">₹{{ number_format($item->product->price, 2) }}</td>
                                                    <td>
                                                        <div class="cart-qty-control">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm qty-dec" data-product-id="{{ $item->product->id }}">-</button>
                                                            <input
                                                                type="number"
                                                                class="form-control form-control-sm cart-qty-input"
                                                                data-product-id="{{ $item->product->id }}"
                                                                data-price="{{ $item->product->price }}"
                                                                value="{{ $item->quantity }}"
                                                                min="1"
                                                                max="{{ $item->product->quantity }}"
                                                            >
                                                            <button type="button" class="btn btn-outline-secondary btn-sm qty-inc" data-product-id="{{ $item->product->id }}">+</button>
                                                        </div>
                                                    </td>
                                                    <td class="cart-item-total fw-bold">₹{{ number_format($item->product->price * $item->quantity, 2) }}</td>
                                                    <td class="text-end">
                                                        <button type="button" class="btn btn-outline-danger btn-sm remove-cart-item" data-product-id="{{ $item->product->id }}">
                                                            <i class="bi bi-trash"></i>
                                                            Remove
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-4">
                        <div class="cart-panel cart-summary p-4">
                            <h2 class="h5 fw-bold mb-3">Order Summary</h2>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <strong data-cart-total>₹{{ number_format($total, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Shipping</span>
                                <span class="fw-semibold">Calculated later</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="fw-bold">Total</span>
                                <strong class="fs-4" data-cart-total-large>₹{{ number_format($total, 2) }}</strong>
                            </div>
                            <button type="submit" class="btn btn-dark btn-lg w-100" id="checkoutButton">
                                <i class="bi bi-bag-check me-2"></i>
                                Buy Now
                            </button>
                            <p class="text-muted small text-center mt-3 mb-0">Only selected items will be included.</p>
                        </div>
                    </div>
                </div>
            </form>
        @else
            <div class="cart-panel cart-empty text-center p-5">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-3" style="width: 72px; height: 72px;">
                    <i class="bi bi-cart-x fs-2 text-muted"></i>
                </div>
                <h2 class="h4 fw-bold">Your cart is empty</h2>
                <p class="text-muted">Add products to your cart and they will show here.</p>
                <a href="{{ route('home') }}" class="btn btn-dark">Start Shopping</a>
            </div>
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function(){
    function formatMoney(amount) {
        return '₹' + parseFloat(amount || 0).toFixed(2);
    }

    function showMessage(type, message) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: type,
            title: message,
            showConfirmButton: false,
            timer: 2600
        });
    }

    function syncCheckoutButton() {
        $('#checkoutButton').prop('disabled', $('.product-checkbox:checked').length === 0);
    }

    $('#selectAll').on('change', function() {
        $('.product-checkbox').prop('checked', this.checked);
        syncCheckoutButton();
    });

    $(document).on('change', '.product-checkbox', function() {
        $('#selectAll').prop('checked', $('.product-checkbox').length === $('.product-checkbox:checked').length);
        syncCheckoutButton();
    });

    function updateCartQuantity($input) {
        let productId = $input.data('product-id');
        let price = parseFloat($input.data('price')) || 0;
        let qty = parseInt($input.val(), 10) || 1;
        let max = parseInt($input.attr('max'), 10) || 1;

        if (qty < 1) qty = 1;
        if (qty > max) {
            qty = max;
            showMessage('warning', 'Only ' + max + ' item(s) available.');
        }

        $input.val(qty);
        $input.closest('tr').find('.cart-item-total').text(formatMoney(price * qty));

        $.ajax({
            url: "{{ url('cart/update') }}/" + productId,
            method: "POST",
            data: {
                quantity: qty,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                if (data.success) {
                    $('[data-cart-total]').text(formatMoney(data.cart_total));
                    $('[data-cart-total-large]').text(formatMoney(data.cart_total));
                    $('[data-header-cart-count]').text(data.cart_count);
                    showMessage('success', data.message || 'Cart updated successfully.');
                } else if (data.error) {
                    showMessage('error', data.error);
                }
            },
            error: function(xhr) {
                showMessage('error', xhr.responseJSON?.error || 'Unable to update cart.');
            }
        });
    }

    $('.cart-qty-input').on('change', function() {
        updateCartQuantity($(this));
    });

    $(document).on('click', '.qty-inc, .qty-dec', function() {
        const $row = $(this).closest('tr');
        const $input = $row.find('.cart-qty-input');
        const current = parseInt($input.val(), 10) || 1;
        const max = parseInt($input.attr('max'), 10) || 1;
        const next = $(this).hasClass('qty-inc') ? Math.min(current + 1, max) : Math.max(current - 1, 1);

        if (next === current) {
            if ($(this).hasClass('qty-inc') && current >= max) {
                showMessage('warning', 'Only ' + max + ' item(s) available.');
            }
            return;
        }

        $input.val(next);
        updateCartQuantity($input);
    });

    $('.remove-cart-item').on('click', function() {
        let productId = $(this).data('product-id');
        let $row = $(this).closest('tr');

        Swal.fire({
            title: 'Remove item?',
            text: 'This product will be removed from your cart.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, remove it'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('cart/remove') }}/" + productId,
                    method: "DELETE",
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(data) {
                        if (data.success) {
                            $row.remove();
                            $('[data-cart-total]').text(formatMoney(data.cart_total));
                            $('[data-cart-total-large]').text(formatMoney(data.cart_total));
                            $('[data-header-cart-count]').text(data.cart_count);
                            showMessage('success', data.message || 'Product removed successfully.');

                            if ($('#cartRows tr').length === 0) {
                                setTimeout(function() {
                                    location.reload();
                                }, 700);
                            }
                        } else if (data.error) {
                            showMessage('error', data.error);
                        }
                    },
                    error: function(xhr) {
                        showMessage('error', xhr.responseJSON?.error || 'Unable to remove item.');
                    }
                });
            }
        });
    });

    $('#buyNowForm').on('submit', function(event) {
        if ($('.product-checkbox:checked').length === 0) {
            event.preventDefault();
            showMessage('warning', 'Please select at least one item.');
        }
    });

    syncCheckoutButton();
});
</script>
@endsection
