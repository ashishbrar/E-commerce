@extends('frontend.layouts.frontend')
@section('content')
<style>
main { margin: 9%; }
.image, .gallery-image {width: 100%;height: 300px;object-fit: contain;padding: 5px;transition: transform 0.3s ease, border 0.3s ease;}
.gallery-image {width: 100px;height: 90px;padding: 3px;}
.gallery-image.selected, .gallery-image:hover,
.image:hover {transform: scale(1.1);border: 2px solid #007bff;}
.product-image-container {width: 100%;max-width: 400px;height: 300px;overflow: hidden;border: 1px solid #eee;background: #fff;border-radius: 8px;position: relative;}
#mainImage {width: 100%;height: 100%;object-fit: contain;transition: transform 0.2s ease;cursor: crosshair;}
#mainImage.zoomed {transform: scale(2.2);box-shadow: 0 4px 20px rgba(0,0,0,0.15);}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="product-image-container position-relative">
                <img id="mainImage" 
                    src="{{ $main_image ? asset('storage/' . $main_image) : asset('frontend/assets/images/default.png') }}" 
                    alt="{{ $product->name ?? 'Product Image' }}"
                    class="image">
                     @php
                                    $inWishlist = false;
                                    if(auth()->check()) {
                                        $inWishlist = \App\Models\Wishlist::where('user_id', auth()->id())
                                            ->where('product_id', $product->id)
                                            ->exists();
                                    }
                                @endphp
                    <form method="POST" action="{{ $inWishlist ? route('wishlist.remove', $product->id) : route('wishlist.add', $product->id) }}" class="position-absolute top-0 end-0 m-2">
                        @csrf
                        <button type="submit" class="btn p-1 border-0" style="box-shadow: none;">
                            @if($inWishlist)
                                <svg width="22" height="22" fill="#e74c3c" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            @else
                            <svg width="22" height="22" fill="none" stroke="#e74c3c" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M20.8 4.6c-1.5-1.4-3.9-1.4-5.4 0l-.4.4-.4-.4c-1.5-1.4-3.9-1.4-5.4 0-1.6 1.5-1.6 4 0 5.5l5.8 5.7 5.8-5.7c1.6-1.5 1.6-4 0-5.5z"/>
                            </svg>
                            @endif
                        </button>
                    </form>
            </div>
            @if(!empty($gallery_images))
            <div class="d-flex flex-wrap mt-2">
                @foreach($gallery_images as $img)
                    <img src="{{ asset('storage/' . $img) }}" 
                         class="gallery-image" 
                         data-full="{{ asset('storage/' . $img) }}" 
                         alt="Gallery"
                         style="cursor: pointer;">
                @endforeach
            </div>
            @endif
        </div>
        <div class="col-md-6">
            <h3>{{ $product->name }}</h3>
            <p class="text-success fs-5">
                @if($product->price)
                    ₹{{ number_format($product->price, 2) }}
                @else
                    <span class="text-muted">No price available</span>
                @endif
            </p>
            <p>{{ $product->description ?? 'No description available.' }}</p>
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('cart.add', $product->id) }}">
                        <button class="btn btn-primary">Add to Cart</button>
                    </a>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('buy.now') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-success ">Buy Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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


    $('#wishlistBtn').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        button.prop('disabled', true);

        $.ajax({
            url: "{{ route('wishlist.add', $product->id) }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}"
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    alert('Please log in to add to your wishlist.');
                } else {
                    alert('An error occurred. Please try again.');
                }
                button.prop('disabled', false);
            }
        });
    });
});
</script>
@endsection
