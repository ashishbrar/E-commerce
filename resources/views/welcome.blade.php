@extends('frontend.layouts.frontend')
@section('content')
<section class="hero-section">
    <div class="container">
        <div class="hero-shell">
            <div class="hero-copy">
                <span class="section-badge">Premium Ecommerce Experience</span>
                <h1>Make browsing, buying, and managing products feel polished on every screen.</h1>
                <p>A smarter storefront with cleaner browsing, stronger product focus, and admin-ready commerce workflows.</p>
                <div class="hero-actions">
                    <a href="{{ route('shop.shop') }}" class="btn btn-dark btn-lg">Shop Collection</a>
                    <a href="#featured" class="btn btn-outline-dark btn-lg">Explore Products</a>
                </div>
                <div class="hero-stats">
                    <div><strong>{{ $products->count() }}</strong><span>Active listings</span></div>
                    <div><strong>24/7</strong><span>Order-ready design</span></div>
                    <div><strong>100%</strong><span>Responsive layout</span></div>
                </div>
            </div>
            <div class="hero-visual">
                <img src="{{ asset('frontend/assets/banner/banner.jpg') }}" alt="Shopping banner" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<section class="py-5" id="why-ace">
    <div class="container">
        <div class="feature-grid">
            <article class="feature-card">
                <i class="bi bi-truck"></i>
                <h3>Fast Fulfillment</h3>
                <p>Built for quick browsing and faster purchase decisions with clean CTAs.</p>
            </article>
            <article class="feature-card">
                <i class="bi bi-shield-check"></i>
                <h3>Trusted Flows</h3>
                <p>Wishlist, cart, buy now, and account actions stay visible and consistent.</p>
            </article>
            <article class="feature-card">
                <i class="bi bi-layout-text-window"></i>
                <h3>Modern UI System</h3>
                <p>Reusable responsive cards, forms, filters, and admin tables across the app.</p>
            </article>
        </div>
    </div>
</section>

<section class="catalog-section pb-5" id="featured">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success admin-alert">{{ session('success') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info admin-alert">{{ session('info') }}</div>
        @endif

        <div class="section-heading">
            <div>
                <span class="section-badge">Featured Products</span>
                <h2>Shop the latest additions</h2>
            </div>
            <a href="{{ route('shop.shop') }}" class="btn btn-outline-dark">View All</a>
        </div>

        <div class="row g-4">
            @forelse($products as $product)
                @php
                    $inWishlist = auth()->check() ? \App\Models\Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->exists() : false;
                    $orderedQty = \App\Models\OrderItem::where('product_id', $product->id)
                        ->whereHas('order', fn ($query) => $query->where('status', 'complete'))
                        ->sum('quantity');
                    $remaining = max(0, $product->quantity - $orderedQty);
                @endphp
                <div class="col-12 col-md-6 col-xl-4 d-flex">
                    <article class="product-card flex-fill">
                        <div class="product-media">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                <div class="product-media-placeholder">No Image</div>
                            @endif

                            <form method="POST" action="{{ $inWishlist ? route('wishlist.remove', $product->id) : route('wishlist.add', $product->id) }}" class="wishlist-form" data-product-id="{{ $product->id }}">
                                @csrf
                                <button type="submit" class="wishlist-toggle" aria-label="Toggle wishlist">
                                    <i class="bi {{ $inWishlist ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                                </button>
                            </form>
                        </div>
                        <div class="product-card-body">
                            <div class="product-meta">{{ $product->shortdescription }}</div>
                            <h3><a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a></h3>
                            <div class="product-price-row">
                                <span class="product-price">₹{{ number_format($product->price, 2) }}</span>
                                <span class="product-stock {{ $remaining > 0 ? 'in-stock' : 'out-stock' }}">
                                    {{ $remaining > 0 ? $remaining . ' left' : 'Out of stock' }}
                                </span>
                            </div>
                            @if($remaining > 0)
                                <div class="product-actions">
                                    <a href="{{ route('cart.add', $product->id) }}" class="btn btn-dark">Add to Cart</a>
                                    <form action="{{ route('buy.now') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="btn btn-outline-dark w-100">Buy Now</button>
                                    </form>
                                </div>
                            @else
                                <div class="product-actions product-actions-single">
                                    <button class="btn btn-outline-secondary w-100" disabled>Currently Unavailable</button>
                                </div>
                            @endif
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <i class="bi bi-box-seam"></i>
                        <h3>No products available</h3>
                        <p>Add products from the admin panel to populate the storefront.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
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
