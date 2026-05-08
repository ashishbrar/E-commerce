@extends('frontend.layouts.frontend')
@section('content')

<style>
    .pdp-wrap {
        --pdp-ink: #0f172a;
        --pdp-muted: #64748b;
        --pdp-line: #e5e7eb;
        --pdp-soft: #f6f8fb;
        --pdp-accent: #2563eb;
        --pdp-success: #16a34a;
        --pdp-danger: #dc2626;
        background: #ffffff;
    }

    .pdp-container {
        max-width: 1200px;
        padding: 28px 16px 64px;
        margin: 0 auto;
    }

    .pdp-breadcrumb {
        font-size: 13px;
        color: var(--pdp-muted);
        margin-bottom: 22px;
    }

    .pdp-breadcrumb a {
        color: var(--pdp-muted);
        text-decoration: none;
    }

    .pdp-breadcrumb a:hover {
        color: var(--pdp-ink);
    }

    .pdp-breadcrumb .sep {
        margin: 0 8px;
        opacity: .55;
    }

    .pdp-breadcrumb .current {
        color: var(--pdp-ink);
        font-weight: 600;
    }

    .pdp-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(0, 1fr);
        gap: 48px;
        align-items: start;
    }

    @media (max-width: 991px) {
        .pdp-grid { grid-template-columns: 1fr; gap: 28px; }
    }

    /* Gallery */
    .pdp-gallery { display: grid; gap: 14px; }

    .pdp-main-image {
        position: relative;
        background: var(--pdp-soft);
        border: 1px solid var(--pdp-line);
        border-radius: 16px;
        overflow: hidden;
        aspect-ratio: 1 / 1;
    }

    .pdp-main-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 24px;
        cursor: zoom-in;
        transition: transform .25s ease;
    }

    .pdp-main-image img.is-zoomed {
        transform: scale(2.2);
        cursor: zoom-out;
    }

    .pdp-wishlist {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 42px;
        height: 42px;
        background: #ffffff;
        border: 1px solid var(--pdp-line);
        border-radius: 50%;
        display: grid;
        place-items: center;
        cursor: pointer;
        transition: transform .2s ease, box-shadow .2s ease;
        box-shadow: 0 4px 12px rgba(15, 23, 42, .08);
    }

    .pdp-wishlist:hover { transform: scale(1.06); box-shadow: 0 8px 18px rgba(15, 23, 42, .14); }

    .pdp-wishlist-form { margin: 0; }

    .pdp-thumbs {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(78px, 1fr));
        gap: 10px;
    }

    .pdp-thumb {
        aspect-ratio: 1 / 1;
        background: var(--pdp-soft);
        border: 1px solid var(--pdp-line);
        border-radius: 10px;
        padding: 6px;
        cursor: pointer;
        transition: border-color .18s ease, transform .18s ease;
        overflow: hidden;
    }

    .pdp-thumb img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .pdp-thumb:hover { transform: translateY(-2px); }
    .pdp-thumb.is-selected { border-color: var(--pdp-accent); box-shadow: 0 0 0 2px rgba(37, 99, 235, .18); }

    /* Info */
    .pdp-info { display: grid; gap: 18px; }

    .pdp-category-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        background: rgba(37, 99, 235, .08);
        color: var(--pdp-accent);
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        width: fit-content;
    }

    .pdp-title {
        margin: 0;
        font-size: clamp(24px, 3vw, 34px);
        font-weight: 800;
        color: var(--pdp-ink);
        letter-spacing: -.01em;
        line-height: 1.18;
    }

    .pdp-price-row {
        display: flex;
        align-items: baseline;
        gap: 12px;
        flex-wrap: wrap;
    }

    .pdp-price {
        font-size: 30px;
        font-weight: 800;
        color: var(--pdp-ink);
    }

    .pdp-price-empty {
        color: var(--pdp-muted);
        font-style: italic;
    }

    .pdp-stock {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .pdp-stock.in {
        background: rgba(22, 163, 74, .12);
        color: var(--pdp-success);
    }

    .pdp-stock.out {
        background: rgba(220, 38, 38, .12);
        color: var(--pdp-danger);
    }

    .pdp-stock .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    .pdp-divider {
        border: 0;
        border-top: 1px solid var(--pdp-line);
        margin: 4px 0;
    }

    .pdp-section-label {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--pdp-muted);
        margin-bottom: 8px;
    }

    .pdp-description {
        color: #334155;
        line-height: 1.65;
        font-size: 15px;
    }

    .pdp-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    @media (max-width: 480px) {
        .pdp-actions { grid-template-columns: 1fr; }
    }

    .pdp-btn {
        appearance: none;
        border: 0;
        border-radius: 12px;
        padding: 14px 18px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: transform .15s ease, background .15s ease, box-shadow .15s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
    }

    .pdp-btn:hover:not(:disabled) { transform: translateY(-1px); }
    .pdp-btn:disabled { opacity: .55; cursor: not-allowed; }

    .pdp-btn-primary {
        background: var(--pdp-ink);
        color: #fff;
    }
    .pdp-btn-primary:hover:not(:disabled) { background: #1e293b; }

    .pdp-btn-accent {
        background: var(--pdp-accent);
        color: #fff;
        box-shadow: 0 8px 22px rgba(37, 99, 235, .25);
    }
    .pdp-btn-accent:hover:not(:disabled) { background: #1d4ed8; }

    .pdp-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        padding: 16px;
        background: var(--pdp-soft);
        border: 1px solid var(--pdp-line);
        border-radius: 12px;
    }

    .pdp-meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #334155;
    }

    .pdp-meta-item i {
        color: var(--pdp-accent);
        font-size: 18px;
    }

    .pdp-meta-item strong {
        display: block;
        color: var(--pdp-ink);
        font-size: 13px;
        font-weight: 700;
    }

    .pdp-meta-item small {
        color: var(--pdp-muted);
        font-size: 12px;
    }

    .pdp-short {
        color: #475569;
        font-size: 15px;
        line-height: 1.6;
    }

    /* Tabs */
    .pdp-tabs-wrap {
        margin-top: 56px;
    }

    .pdp-tabs {
        display: flex;
        gap: 4px;
        border-bottom: 1px solid var(--pdp-line);
        overflow-x: auto;
        margin-bottom: 24px;
    }

    .pdp-tab {
        appearance: none;
        background: transparent;
        border: 0;
        padding: 14px 18px;
        font-size: 14px;
        font-weight: 700;
        color: var(--pdp-muted);
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        white-space: nowrap;
        transition: color .15s ease, border-color .15s ease;
    }

    .pdp-tab:hover { color: var(--pdp-ink); }

    .pdp-tab.is-active {
        color: var(--pdp-ink);
        border-bottom-color: var(--pdp-ink);
    }

    .pdp-tab-panel {
        display: none;
        animation: pdpFadeIn .2s ease;
    }

    .pdp-tab-panel.is-active { display: block; }

    @keyframes pdpFadeIn {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .pdp-prose {
        color: #334155;
        line-height: 1.75;
        font-size: 15px;
        max-width: 820px;
    }

    .pdp-prose p { margin: 0 0 14px; }

    .pdp-specs {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 10px;
        max-width: 820px;
    }

    .pdp-spec {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 14px;
        background: var(--pdp-soft);
        border-radius: 10px;
        font-size: 14px;
    }

    .pdp-spec-label { color: var(--pdp-muted); font-weight: 600; }
    .pdp-spec-value { color: var(--pdp-ink); font-weight: 700; text-align: right; word-break: break-word; }

    .pdp-policy {
        display: grid;
        gap: 16px;
        max-width: 820px;
    }

    .pdp-policy-card {
        display: flex;
        gap: 14px;
        padding: 16px;
        border: 1px solid var(--pdp-line);
        border-radius: 12px;
        background: #fff;
    }

    .pdp-policy-card i {
        color: var(--pdp-accent);
        font-size: 22px;
        line-height: 1.2;
    }

    .pdp-policy-card h4 {
        margin: 0 0 4px;
        font-size: 15px;
        font-weight: 800;
        color: var(--pdp-ink);
    }

    .pdp-policy-card p {
        margin: 0;
        color: var(--pdp-muted);
        font-size: 13px;
        line-height: 1.55;
    }

    /* Related products */
    .pdp-related {
        margin-top: 64px;
    }

    .pdp-related-head {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 20px;
    }

    .pdp-related-head h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        color: var(--pdp-ink);
    }

    .pdp-related-head a {
        color: var(--pdp-accent);
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
    }

    .pdp-related-head a:hover { text-decoration: underline; }

    .pdp-related-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
    }

    @media (max-width: 900px) {
        .pdp-related-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 640px) {
        .pdp-related-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    }

    .pdp-related-card {
        display: block;
        background: #fff;
        border: 1px solid var(--pdp-line);
        border-radius: 10px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .pdp-related-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, .08);
    }

    .pdp-related-img {
        aspect-ratio: 1 / 1;
        background: var(--pdp-soft);
        display: grid;
        place-items: center;
        overflow: hidden;
    }

    .pdp-related-img img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 10px;
    }

    .pdp-related-body { padding: 8px 10px 10px; }

    .pdp-related-cat {
        font-size: 9px;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
        color: var(--pdp-accent);
    }

    .pdp-related-name {
        margin: 3px 0 4px;
        font-size: 12px;
        font-weight: 700;
        color: var(--pdp-ink);
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .pdp-related-price {
        font-size: 12px;
        font-weight: 800;
        color: var(--pdp-ink);
    }
</style>

<div class="pdp-wrap">
    <div class="pdp-container">

        <nav class="pdp-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">/</span>
            <a href="{{ route('shop.shop') }}">Shop</a>
            <span class="sep">/</span>
            <span class="current">{{ $product->name }}</span>
        </nav>

        <div class="pdp-grid">

            <div class="pdp-gallery">
                <div class="pdp-main-image">
                    <img id="mainImage"
                         src="{{ $main_image ? asset('storage/' . $main_image) : asset('frontend/assets/images/default.png') }}"
                         alt="{{ $product->name ?? 'Product Image' }}">

                    @php
                        $inWishlist = false;
                        if (auth()->check()) {
                            $inWishlist = \App\Models\Wishlist::where('user_id', auth()->id())
                                ->where('product_id', $product->id)
                                ->exists();
                        }
                    @endphp
                    <form method="POST"
                          action="{{ $inWishlist ? route('wishlist.remove', $product->id) : route('wishlist.add', $product->id) }}"
                          class="pdp-wishlist-form">
                        @csrf
                        <button type="submit" class="pdp-wishlist" aria-label="{{ $inWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}" title="{{ $inWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}">
                            @if($inWishlist)
                                <svg width="20" height="20" fill="#e74c3c" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            @else
                                <svg width="20" height="20" fill="none" stroke="#e74c3c" stroke-width="2" viewBox="0 0 24 24"><path d="M20.8 4.6c-1.5-1.4-3.9-1.4-5.4 0l-.4.4-.4-.4c-1.5-1.4-3.9-1.4-5.4 0-1.6 1.5-1.6 4 0 5.5l5.8 5.7 5.8-5.7c1.6-1.5 1.6-4 0-5.5z"/></svg>
                            @endif
                        </button>
                    </form>
                </div>

                @if(!empty($gallery_images))
                    <div class="pdp-thumbs">
                        @foreach($gallery_images as $img)
                            <div class="pdp-thumb" data-full="{{ asset('storage/' . $img) }}">
                                <img src="{{ asset('storage/' . $img) }}" alt="Gallery">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="pdp-info">
                @if($product->category)
                    <span class="pdp-category-pill">
                        <i class="bi bi-tag-fill"></i>
                        {{ $product->category->name }}
                    </span>
                @endif

                <h1 class="pdp-title">{{ $product->name }}</h1>

                @if(!empty($product->shortdescription))
                    <p class="pdp-short">{{ $product->shortdescription }}</p>
                @endif

                <div class="pdp-price-row">
                    @if($product->price)
                        <span class="pdp-price">₹{{ number_format($product->price, 2) }}</span>
                    @else
                        <span class="pdp-price-empty">No price available</span>
                    @endif

                    @if($product->quantity > 0)
                        <span class="pdp-stock in">
                            <span class="dot"></span> In stock — {{ $product->quantity }} left
                        </span>
                    @else
                        <span class="pdp-stock out">
                            <span class="dot"></span> Out of stock
                        </span>
                    @endif
                </div>

                <hr class="pdp-divider">

                <div class="pdp-actions">
                    @if($product->quantity > 0)
                        <a href="{{ route('cart.add', $product->id) }}" class="pdp-btn pdp-btn-primary">
                            <i class="bi bi-bag-plus"></i> Add to Cart
                        </a>
                        <form action="{{ route('buy.now') }}" method="POST" style="margin:0;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="pdp-btn pdp-btn-accent" style="width:100%;">
                                <i class="bi bi-lightning-charge-fill"></i> Buy Now
                            </button>
                        </form>
                    @else
                        <button class="pdp-btn pdp-btn-primary" disabled>Sold Out</button>
                        <button class="pdp-btn pdp-btn-accent" disabled>Buy Now</button>
                    @endif
                </div>

                <div class="pdp-meta">
                    <div class="pdp-meta-item">
                        <i class="bi bi-truck"></i>
                        <div>
                            <strong>Free shipping</strong>
                            <small>On prepaid orders over ₹999</small>
                        </div>
                    </div>
                    <div class="pdp-meta-item">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <div>
                            <strong>Easy returns</strong>
                            <small>7-day return policy</small>
                        </div>
                    </div>
                    <div class="pdp-meta-item">
                        <i class="bi bi-shield-check"></i>
                        <div>
                            <strong>Secure checkout</strong>
                            <small>SSL-encrypted payments</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <section class="pdp-tabs-wrap">
            <div class="pdp-tabs" role="tablist">
                <button class="pdp-tab is-active" data-pdp-tab="description" role="tab" aria-selected="true">Description</button>
                <button class="pdp-tab" data-pdp-tab="details" role="tab" aria-selected="false">Details</button>
                <button class="pdp-tab" data-pdp-tab="shipping" role="tab" aria-selected="false">Shipping &amp; Returns</button>
            </div>

            <div class="pdp-tab-panel is-active" data-pdp-panel="description" role="tabpanel">
                <div class="pdp-prose">
                    @if(!empty($product->description))
                        @foreach(preg_split("/\r\n|\n|\r/", trim($product->description)) as $paragraph)
                            @if(trim($paragraph) !== '')
                                <p>{{ $paragraph }}</p>
                            @endif
                        @endforeach
                    @else
                        <p>No detailed description available for this product.</p>
                    @endif
                </div>
            </div>

            <div class="pdp-tab-panel" data-pdp-panel="details" role="tabpanel">
                <div class="pdp-specs">
                    <div class="pdp-spec">
                        <span class="pdp-spec-label">Product ID</span>
                        <span class="pdp-spec-value">#{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    @if($product->category)
                        <div class="pdp-spec">
                            <span class="pdp-spec-label">Category</span>
                            <span class="pdp-spec-value">{{ $product->category->name }}</span>
                        </div>
                    @endif
                    <div class="pdp-spec">
                        <span class="pdp-spec-label">Availability</span>
                        <span class="pdp-spec-value">
                            @if($product->quantity > 0)
                                {{ $product->quantity }} in stock
                            @else
                                Out of stock
                            @endif
                        </span>
                    </div>
                    @if($product->price)
                        <div class="pdp-spec">
                            <span class="pdp-spec-label">Price</span>
                            <span class="pdp-spec-value">₹{{ number_format($product->price, 2) }}</span>
                        </div>
                    @endif
                    <div class="pdp-spec">
                        <span class="pdp-spec-label">Status</span>
                        <span class="pdp-spec-value">{{ ucfirst($product->status ?? 'Active') }}</span>
                    </div>
                    <div class="pdp-spec">
                        <span class="pdp-spec-label">Added on</span>
                        <span class="pdp-spec-value">{{ optional($product->created_at)->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="pdp-tab-panel" data-pdp-panel="shipping" role="tabpanel">
                <div class="pdp-policy">
                    <div class="pdp-policy-card">
                        <i class="bi bi-truck"></i>
                        <div>
                            <h4>Standard delivery</h4>
                            <p>Orders are dispatched within 1–2 business days. Delivery typically takes 3–7 business days depending on location. Free for prepaid orders over ₹999.</p>
                        </div>
                    </div>
                    <div class="pdp-policy-card">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <div>
                            <h4>7-day easy returns</h4>
                            <p>Not satisfied? Return unused items in original packaging within 7 days of delivery for a full refund or exchange.</p>
                        </div>
                    </div>
                    <div class="pdp-policy-card">
                        <i class="bi bi-shield-check"></i>
                        <div>
                            <h4>Secure payments</h4>
                            <p>All transactions are encrypted with industry-standard SSL. We support Stripe, PayPal, and Cash on Delivery in supported regions.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @if(!empty($related_products) && count($related_products) > 0)
            <section class="pdp-related">
                <div class="pdp-related-head">
                    <h2>You may also like</h2>
                    <a href="{{ route('shop.shop') }}">Browse all <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="pdp-related-grid">
                    @foreach($related_products as $rel)
                        <a href="{{ route('products.show', $rel->id) }}" class="pdp-related-card">
                            <div class="pdp-related-img">
                                <img src="{{ $rel->image ? asset('storage/' . $rel->image) : asset('frontend/assets/images/default.png') }}"
                                     alt="{{ $rel->name }}">
                            </div>
                            <div class="pdp-related-body">
                                @if($rel->category)
                                    <div class="pdp-related-cat">{{ $rel->category->name }}</div>
                                @endif
                                <div class="pdp-related-name">{{ $rel->name }}</div>
                                <div class="pdp-related-price">
                                    @if($rel->price)
                                        ₹{{ number_format($rel->price, 2) }}
                                    @else
                                        <span style="color: var(--pdp-muted); font-weight: 600;">—</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mainImage = document.getElementById('mainImage');
    const thumbs = document.querySelectorAll('.pdp-thumb');

    if (thumbs.length > 0) {
        thumbs[0].classList.add('is-selected');
    }

    function selectThumb(thumb) {
        if (!thumb || !mainImage) return;
        mainImage.src = thumb.dataset.full;
        thumbs.forEach(t => t.classList.remove('is-selected'));
        thumb.classList.add('is-selected');
    }

    thumbs.forEach(thumb => {
        thumb.addEventListener('mouseenter', () => selectThumb(thumb));
        thumb.addEventListener('click', () => selectThumb(thumb));
    });

    // Hover-zoom (mouse) + tap-toggle (touch).
    if (mainImage) {
        let zoomed = false;
        const container = mainImage.parentElement;

        mainImage.addEventListener('mouseenter', () => {
            zoomed = true;
            mainImage.classList.add('is-zoomed');
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
            mainImage.classList.remove('is-zoomed');
            mainImage.style.transformOrigin = 'center center';
        });

        let lastTap = 0;
        mainImage.addEventListener('touchend', function () {
            const now = Date.now();
            if (now - lastTap < 400) {
                zoomed = !zoomed;
                mainImage.classList.toggle('is-zoomed', zoomed);
                if (!zoomed) mainImage.style.transformOrigin = 'center center';
            }
            lastTap = now;
        });
    }

    // Tabs
    const tabs = document.querySelectorAll('[data-pdp-tab]');
    const panels = document.querySelectorAll('[data-pdp-panel]');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.getAttribute('data-pdp-tab');
            tabs.forEach(t => {
                const active = t === tab;
                t.classList.toggle('is-active', active);
                t.setAttribute('aria-selected', active ? 'true' : 'false');
            });
            panels.forEach(p => {
                p.classList.toggle('is-active', p.getAttribute('data-pdp-panel') === target);
            });
        });
    });
});
</script>

@endsection
