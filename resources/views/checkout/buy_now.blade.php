@extends('frontend.layouts.frontend')
@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Your Products</h5>
                </div>
                <div class="card-body">
                    @php $grandTotal = 0; @endphp
                    <form id="checkoutForm" method="POST" action="{{ route('buy.now.placeOrder') }}">
                        @csrf

                        @foreach($products as $product)
                        @php
                        $cartItem = $cart->firstWhere('product_id', $product->id);
                        $quantity = $cartItem ? min($cartItem->quantity, $product->quantity) : 1;
                        $subtotal = $product->price * $quantity;
                        $grandTotal += $subtotal;
                        @endphp

                        <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                        <input type="hidden" name="product_prices[{{ $product->id }}]" value="{{ $product->price }}">
                        <input type="hidden" name="product_names[{{ $product->id }}]" value="{{ $product->name }}">
                        <input type="hidden" name="quantity[{{ $product->id }}]" value="{{ $quantity }}">

                        <div class="d-flex align-items-center mb-3 p-2 border rounded">
                            <img src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                                class="img-thumbnail me-3"
                                style="width: 80px; height: 80px; object-fit: cover;"
                                onerror="this.onerror=null;this.src='{{ asset('images/default-product.png') }}';">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $product->name }}</h6>
                                <p class="mb-1 text-muted">Price: ₹{{ number_format($product->price, 2) }}</p>
                                <p class="mb-1 {{ $product->quantity > 0 ? 'text-muted' : 'text-danger fw-semibold' }}">
                                    {{ $product->quantity > 0 ? 'Available: ' . $product->quantity : 'Out of stock' }}
                                </p>
                                <label class="small mb-1">Quantity:</label>
                                <input type="number"
                                    name="quantity[{{ $product->id }}]"
                                    value="{{ $quantity }}"
                                    min="1"
                                    max="{{ $product->quantity }}"
                                    class="form-control form-control-sm w-50 checkout-qty"
                                    data-price="{{ $product->price }}">
                            </div>
                            <div class="ms-3">
                                <strong>₹{{ number_format($subtotal, 2) }}</strong>
                            </div>
                        </div>
                        @endforeach

                        <div class="mt-3 p-3 bg-light border rounded d-flex justify-content-between">
                            <h6>Total</h6>
                            <h6>₹{{ number_format($grandTotal, 2) }}</h6>
                        </div>

                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Billing & Shipping</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" name="full_name" id="full_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" name="phone" id="phone" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Shipping Address</label>
                        <input type="text" name="address" id="address" class="form-control" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input type="text" name="city" id="city" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" id="postal_code" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Payment Method</label><br>
                        <input type="radio" name="payment_method" value="cod" checked> Cash on Delivery <br>
                        <input type="radio" name="payment_method" value="stripe"> Pay with Stripe <br>
                        @if(Route::has('paypal.checkout'))
                            <input type="radio" name="payment_method" value="paypal"> Pay with PayPal <br>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        let invalidStock = false;

        document.querySelectorAll('.checkout-qty').forEach(function(input) {
            const max = parseInt(input.getAttribute('max'), 10) || 0;
            const qty = parseInt(input.value, 10) || 1;

            if (max < 1 || qty > max) {
                invalidStock = true;
            }
        });

        if (invalidStock) {
            e.preventDefault();
            alert('Some selected products are out of stock or exceed available stock.');
            return;
        }

        if (paymentMethod === 'stripe') {
            e.preventDefault();
            this.action = "{{ route('stripe.checkout') }}";
            this.submit();
        }
        if (paymentMethod === 'paypal') {
            e.preventDefault();
            this.action = "{{ route('paypal.checkout') }}";
            this.submit();
        }
    });
</script>
@endsection
