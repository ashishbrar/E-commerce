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
                                $quantity = $cartItem ? $cartItem->quantity : 1;
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
                                    <label class="small mb-1">Quantity:</label>
                                    <input type="number" value="{{ $quantity }}" min="1" class="form-control form-control-sm w-50">
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
                                <input type="radio" name="payment_method" value="paypal"> Pay with PayPal
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

    if (paymentMethod === 'paypal') {
        e.preventDefault();

        const productInputs = Array.from(document.querySelectorAll('input[name="product_ids[]"]'));
        if (productInputs.length === 0) {
            alert('No products found for PayPal checkout.');
            return;
        }

        const params = new URLSearchParams();

        ['full_name','email','phone','address','city','postal_code'].forEach(function(name) {
            const el = document.querySelector(`[name="${name}"]`);
            if (el) params.append(name, el.value);
        });

        params.append('payment_method', 'paypal');

        productInputs.forEach(function(inp) {
            const id = inp.value;
            params.append('product_ids[]', id);

            const qtyEl = document.querySelector(`input[name="quantity[${id}]"]`);
            const qty = qtyEl ? qtyEl.value : '1';
            params.append(`quantity[${id}]`, qty);

            const priceEl = document.querySelector(`input[name="product_prices[${id}]"]`);
            if (priceEl) params.append(`product_prices[${id}]`, priceEl.value);

            const nameEl = document.querySelector(`input[name="product_names[${id}]"]`);
            if (nameEl) params.append(`product_names[${id}]`, nameEl.value);
        });

        const url = "{{ route('paypal.payment') }}" + '?' + params.toString();
        window.location.href = url;
    }
});
</script>
@endsection
