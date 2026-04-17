@extends('frontend.layouts.frontend')
@section('content')
<style>.card:hover {transform: scale(1.02);box-shadow: 0 4px 20px rgba(0,0,0,0.1);transition: all 0.3s ease-in-out;}</style>
<main>
    <div class="main">
        <div class="banner mb-4">
            <img src="{{ asset('frontend/assets/banner/banner.jpg') }}" alt="Banner Image" class="img-fluid w-100">
        </div>

        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show text-center fw-semibold" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show text-center fw-semibold" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <h2 class="mb-5 text-center fw-bold" style="letter-spacing: 2px; color: #333;">Our Products</h2>
            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-12 col-sm-6 col-md-4 d-flex">
                        <div class="card flex-fill border-0 shadow-lg rounded-4 overflow-hidden">
                            <div class="bg-white position-relative d-flex align-items-center justify-content-center" style="height: 220px;">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 180px; object-fit: contain;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif

                                @php
                                    $inWishlist = false;
                                    if(auth()->check()) {
                                        $inWishlist = \App\Models\Wishlist::where('user_id', auth()->id())
                                            ->where('product_id', $product->id)
                                            ->exists();
                                    }
                                @endphp
                                <form method="POST"action="{{ $inWishlist ? route('wishlist.remove', $product->id) : route('wishlist.add', $product->id) }}"class="wishlist-form position-absolute top-0 end-0 m-2"data-product-id="{{ $product->id }}">
                                    @csrf
                                    <button type="submit" class="btn p-1 border-0" style="box-shadow: none;">
                                        @if($inWishlist)
                                            <svg width="22" height="22" fill="#e74c3c" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                        @else
                                            <svg width="22" height="22" fill="none" stroke="#e74c3c" stroke-width="2" viewBox="0 0 24 24"><path d="M20.8 4.6c-1.5-1.4-3.9-1.4-5.4 0l-.4.4-.4-.4c-1.5-1.4-3.9-1.4-5.4 0-1.6 1.5-1.6 4 0 5.5l5.8 5.7 5.8-5.7c1.6-1.5 1.6-4 0-5.5z"/></svg>
                                        @endif
                                    </button>
                                </form>
                            </div>
                            <div class="card-body d-flex flex-column p-4">
                                <h6 class="card-subtitle mb-2 text-muted text-truncate" title="{{ $product->shortdescription }}">
                                    {{ $product->shortdescription }}
                                </h6>
                                <h5 class="card-title mb-3 text-truncate" title="{{ $product->name }}">
                                    <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark fw-semibold">
                                        {{ $product->name }}
                                    </a>
                                </h5>
                                <div class="mb-3">
                                    <span style="font-size: 1.1rem;">
                                        ₹{{ number_format($product->price, 2) }}
                                    </span>
                                </div>      
                                @php
                                $orderedQty = \App\Models\OrderItem::where('product_id', $product->id)
                                    ->whereHas('order', function($q) {
                                        $q->where('status', 'complete');
                                    })
                                    ->sum('quantity');
                                $remaining = $product->quantity - $orderedQty;
                            @endphp
                            @if($remaining > 0)
                                <a href="{{ route('cart.add', $product->id) }}">
                                    <button type="submit" class="btn btn-primary w-100">
                                        Add to Cart 
                                    </button>
                                </a>
                                  <form action="{{ route('buy.now') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-warning mt-2 w-100">Buy Now</button>
                                </form>
                            @else
                                <div class="row">
                                    <div class="col-md-6">
                                        <button class="btn btn-outline-danger w-100" disabled>Out of Stock</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-outline-secondary w-100">Notify Me</button>
                                    </div>
                                </div>
                            @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted fs-4">No products available.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</main>
<script>
$(function() {
    $('.wishlist-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        const formData = form.serialize();

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            success: function() {
                location.reload();
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    window.location.href = "{{ route('login') }}";
                } else {
                    alert('Something went wrong. Please try again.');
                }
            }
        });
    });
});
</script>
@endsection
