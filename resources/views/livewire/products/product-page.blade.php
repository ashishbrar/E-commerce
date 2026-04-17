@extends('frontend.layouts.frontend')
@section('content')
@php
    $inWishlist = auth()->check() ? \App\Models\Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->exists() : false;
@endphp

<section class="product-detail-section">
    <div class="container">
        <div class="product-detail-card">
            <div class="row g-5 align-items-start">
                <div class="col-lg-6">
                    <div class="product-gallery-panel">
                        <div class="product-image-container position-relative">
                            <img id="mainImage" src="{{ $main_image ? asset('storage/' . $main_image) : asset('frontend/assets/banner/banner.jpg') }}" alt="{{ $product->name ?? 'Product Image' }}" class="image">
                            <form method="POST" action="{{ $inWishlist ? route('wishlist.remove', $product->id) : route('wishlist.add', $product->id) }}" class="position-absolute top-0 end-0 m-3">
                                @csrf
                                <button type="submit" class="wishlist-toggle" aria-label="Toggle wishlist">
                                    <i class="bi {{ $inWishlist ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                                </button>
                            </form>
                        </div>

                        @if(!empty($gallery_images))
                            <div class="product-thumbs">
                                @foreach($gallery_images as $img)
                                    <img src="{{ asset('storage/' . $img) }}" class="gallery-image" data-full="{{ asset('storage/' . $img) }}" alt="Gallery">
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="section-badge">Product Detail</div>
                    <h1 class="product-detail-title">{{ $product->name }}</h1>
                    <div class="product-price-large">
                        @if($product->price)
                            ₹{{ number_format($product->price, 2) }}
                        @else
                            <span class="text-muted">No price available</span>
                        @endif
                    </div>
                    <p class="product-description">{{ $product->description ?? 'No description available.' }}</p>

                    <div class="product-detail-info">
                        <div><span>Category</span><strong>{{ $product->category?->name ?? 'General' }}</strong></div>
                        <div><span>Status</span><strong>{{ $product->status ?? 'Active' }}</strong></div>
                        <div><span>Availability</span><strong>{{ $product->quantity ?? 0 }} units</strong></div>
                    </div>

                    <div class="product-detail-actions">
                        <a href="{{ route('cart.add', $product->id) }}" class="btn btn-dark btn-lg">Add to Cart</a>
                        <form action="{{ route('buy.now') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-outline-dark btn-lg w-100">Buy Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mainImage = document.getElementById('mainImage');
    const thumbnails = document.querySelectorAll('.gallery-image');

    if (thumbnails.length > 0) {
        mainImage.src = thumbnails[0].dataset.full;
        thumbnails[0].classList.add('selected');
    }

    thumbnails.forEach(img => {
        img.addEventListener('mouseenter', () => {
            mainImage.src = img.dataset.full;
            thumbnails.forEach(i => i.classList.remove('selected'));
            img.classList.add('selected');
        });

        img.addEventListener('click', () => {
            mainImage.src = img.dataset.full;
            thumbnails.forEach(i => i.classList.remove('selected'));
            img.classList.add('selected');
        });
    });

    let zoomed = false;
    const container = mainImage.parentElement;

    mainImage.addEventListener('mouseenter', () => {
        zoomed = true;
        mainImage.classList.add('zoomed');
    });

    mainImage.addEventListener('mousemove', e => {
        if (!zoomed) return;
        const rect = container.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        mainImage.style.transformOrigin = `${x}% ${y}%`;
    });

    mainImage.addEventListener('mouseleave', () => {
        zoomed = false;
        mainImage.classList.remove('zoomed');
        mainImage.style.transformOrigin = 'center center';
    });

    let lastTap = 0;
    mainImage.addEventListener('touchend', function () {
        const now = Date.now();
        if (now - lastTap < 400) {
            zoomed = !zoomed;
            mainImage.classList.toggle('zoomed', zoomed);
            if (!zoomed) {
                mainImage.style.transformOrigin = 'center center';
            }
        }
        lastTap = now;
    });
});
</script>
@endsection
