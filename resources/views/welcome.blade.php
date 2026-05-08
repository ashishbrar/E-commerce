@extends('frontend.layouts.frontend')

@section('content')
<main class="home-page">
    <section class="home-hero">
        <div class="home-hero-media">
            <img src="{{ asset('frontend/assets/banner/banner.jpg') }}" alt="AceStore seasonal collection">
        </div>
        <div class="home-hero-shade"></div>

        <div class="container home-hero-content">
            <div class="home-hero-copy">
                <span class="home-pill">New season essentials</span>
                <h1>Smart picks for everyday shopping.</h1>
                <p>Discover curated products, quick checkout, reliable stock updates, and a cleaner shopping experience built around what customers actually need.</p>
                <div class="home-hero-actions">
                    <a href="{{ route('shop.shop') }}" class="btn btn-light btn-lg">Shop Collection</a>
                    <a href="#products" class="btn btn-outline-light btn-lg">View Arrivals</a>
                </div>
            </div>

            <div class="home-hero-card">
                <span>Today only</span>
                <strong>Fresh arrivals are live</strong>
                <p>Browse available products before they sell out.</p>
                <a href="#products">Explore now <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <section class="home-trust">
        <div class="container">
            <div class="row g-3">
                @foreach([
                    ['bi-truck', 'Fast Delivery', 'Reliable shipping on every order'],
                    ['bi-shield-check', 'Secure Payments', 'Protected checkout experience'],
                    ['bi-bag-check', 'Live Stock', 'Clear sold-out product states'],
                    ['bi-headset', 'Support', 'Help for orders and returns'],
                ] as $item)
                    <div class="col-6 col-lg-3">
                        <div class="home-trust-item">
                            <i class="bi {{ $item[0] }}"></i>
                            <div>
                                <strong>{{ $item[1] }}</strong>
                                <span>{{ $item[2] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="home-section" id="categories">
        <div class="container">
            <div class="home-section-head">
                <div>
                    <span class="section-kicker">Shop by category</span>
                    <h2>Find the right section faster.</h2>
                </div>
                <a href="{{ route('shop.shop') }}" class="home-text-link">Browse all <i class="bi bi-arrow-right"></i></a>
            </div>

            <div class="row g-3">
                @forelse($categories as $category)
                    <div class="col-sm-6 col-lg-3">
                        <a href="{{ route('shop.shop', ['category' => $category->id]) }}" class="home-category">
                            <span class="home-category-icon"><i class="bi bi-grid-1x2"></i></span>
                            <strong>{{ $category->name }}</strong>
                            <small>{{ $category->products_count }} products</small>
                        </a>
                    </div>
                @empty
                    @foreach(['New Arrivals', 'Daily Essentials', 'Premium Picks', 'Best Sellers'] as $label)
                        <div class="col-sm-6 col-lg-3">
                            <a href="{{ route('shop.shop') }}" class="home-category">
                                <span class="home-category-icon"><i class="bi bi-grid-1x2"></i></span>
                                <strong>{{ $label }}</strong>
                                <small>Explore products</small>
                            </a>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    <section class="home-section home-products-section" id="products">
        <div class="container">
            <div class="home-section-head">
                <div>
                    <span class="section-kicker">Featured products</span>
                    <h2>Latest picks from the store.</h2>
                </div>
                <a href="{{ route('shop.shop') }}" class="home-text-link">View shop <i class="bi bi-arrow-right"></i></a>
            </div>

            <div class="row g-3 g-lg-4">
                @forelse($products as $product)
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                        <article class="home-product-card">
                            <a href="{{ route('products.show', $product->id) }}" class="home-product-image">
                                @if($product->quantity > 0)
                                    <span class="home-product-badge">In stock</span>
                                @else
                                    <span class="home-product-badge sold">Sold out</span>
                                @endif

                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                @else
                                    <div class="home-product-placeholder">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </a>

                            <div class="home-product-body">
                                <div class="home-product-meta">
                                    <span>{{ $product->category->name ?? 'Featured' }}</span>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <h3>
                                    <a href="{{ route('products.show', $product->id) }}">{{ Str::limit($product->name, 48) }}</a>
                                </h3>
                                <div class="home-product-foot">
                                    <strong>₹{{ number_format($product->price, 2) }}</strong>
                                    @if($product->quantity > 0)
                                        <a href="{{ route('cart.add', $product->id) }}" class="home-cart-btn" aria-label="Add {{ $product->name }} to cart">
                                            <i class="bi bi-bag-plus"></i>
                                        </a>
                                    @else
                                        <button type="button" class="home-cart-btn disabled" disabled>
                                            <i class="bi bi-bag-x"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="home-empty">
                            <i class="bi bi-box-seam"></i>
                            <h3>No products available right now.</h3>
                            <p>Add products from the dashboard and they will appear here.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="home-promo">
        <div class="container">
            <div class="home-promo-inner">
                <div>
                    <span class="section-kicker">Better checkout</span>
                    <h2>Cart, wishlist, and stock now stay in sync.</h2>
                    <p>Customers can browse confidently with updated counts, visible sold-out states, and checkout controls that respect available inventory.</p>
                </div>
                <a href="{{ route('shop.shop') }}" class="btn btn-light btn-lg">Start shopping</a>
            </div>
        </div>
    </section>
</main>

<style>
    .home-page {
        background: #f6f8fb;
        overflow-x: hidden;
    }

    .home-hero {
        position: relative;
        min-height: 620px;
        display: flex;
        align-items: center;
        overflow: hidden;
        background: #0f172a;
    }

    .home-hero-media,
    .home-hero-shade {
        position: absolute;
        inset: 0;
    }

    .home-hero-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: .62;
    }

    .home-hero-shade {
        background: linear-gradient(90deg, rgba(15, 23, 42, .94) 0%, rgba(15, 23, 42, .72) 44%, rgba(15, 23, 42, .2) 100%);
    }

    .home-hero-content {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: minmax(0, 650px) minmax(260px, 360px);
        align-items: end;
        justify-content: space-between;
        gap: 32px;
        padding-top: 90px;
        padding-bottom: 82px;
    }

    .home-pill {
        display: inline-flex;
        margin-bottom: 18px;
        border: 1px solid rgba(255, 255, 255, .22);
        border-radius: 999px;
        background: rgba(255, 255, 255, .1);
        color: #fff;
        font-size: 13px;
        font-weight: 850;
        padding: 9px 14px;
    }

    .home-hero h1 {
        max-width: 740px;
        margin: 0;
        color: #fff;
        font-size: clamp(44px, 6vw, 76px);
        font-weight: 950;
        line-height: .98;
        letter-spacing: 0;
    }

    .home-hero p {
        max-width: 600px;
        margin: 22px 0 0;
        color: rgba(255, 255, 255, .78);
        font-size: 18px;
        line-height: 1.7;
    }

    .home-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 32px;
    }

    .home-hero-card {
        width: 100%;
        border: 1px solid rgba(255, 255, 255, .16);
        border-radius: 18px;
        background: rgba(255, 255, 255, .12);
        color: #fff;
        backdrop-filter: blur(18px);
        padding: 24px;
        box-shadow: 0 24px 60px rgba(0, 0, 0, .24);
    }

    .home-hero-card span {
        color: rgba(255, 255, 255, .7);
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .home-hero-card strong {
        display: block;
        margin-top: 10px;
        font-size: 24px;
        line-height: 1.18;
    }

    .home-hero-card p {
        margin: 10px 0 18px;
        font-size: 14px;
    }

    .home-hero-card a {
        color: #fff;
        font-weight: 850;
        text-decoration: none;
    }

    .home-trust {
        margin-top: -42px;
        position: relative;
        z-index: 2;
    }

    .home-trust-item {
        height: 100%;
        display: flex;
        align-items: center;
        gap: 14px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #fff;
        padding: 18px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, .08);
    }

    .home-trust-item i {
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 20px;
    }

    .home-trust-item strong,
    .home-trust-item span {
        display: block;
    }

    .home-trust-item strong {
        color: #111827;
        font-weight: 900;
    }

    .home-trust-item span {
        color: #64748b;
        font-size: 13px;
    }

    .home-section {
        padding: 84px 0 0;
    }

    .home-section-head {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 28px;
    }

    .home-section h2 {
        margin: 6px 0 0;
        color: #111827;
        font-size: clamp(30px, 4vw, 44px);
        font-weight: 950;
        letter-spacing: 0;
    }

    .home-text-link {
        color: #111827;
        font-weight: 850;
        text-decoration: none;
        white-space: nowrap;
    }

    .home-text-link:hover {
        color: #2563eb;
    }

    .home-category {
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        background: #fff;
        color: #111827;
        padding: 22px;
        text-decoration: none;
        box-shadow: 0 14px 32px rgba(15, 23, 42, .05);
        transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
    }

    .home-category:hover,
    .home-product-card:hover {
        transform: translateY(-3px);
        border-color: #cbd5e1;
        box-shadow: 0 24px 48px rgba(15, 23, 42, .1);
    }

    .home-category-icon {
        width: 48px;
        height: 48px;
        display: grid;
        place-items: center;
        margin-bottom: 24px;
        border-radius: 14px;
        background: #f1f5f9;
        color: #2563eb;
        font-size: 21px;
    }

    .home-category strong {
        font-size: 18px;
        font-weight: 950;
    }

    .home-category small {
        margin-top: 5px;
        color: #64748b;
        font-weight: 650;
    }

    .home-product-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 14px 32px rgba(15, 23, 42, .05);
        transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
    }

    .home-product-image {
        position: relative;
        height: 228px;
        display: grid;
        place-items: center;
        background: radial-gradient(circle at top, #f8fafc 0%, #eef2f7 92%);
        padding: 18px;
        text-decoration: none;
    }

    .home-product-image img {
        width: auto;
        height: auto;
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        object-position: center;
        transition: transform .22s ease;
    }

    .home-product-card:hover .home-product-image img {
        transform: scale(1.04);
    }

    .home-product-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        border-radius: 999px;
        background: #dcfce7;
        color: #166534;
        font-size: 12px;
        font-weight: 850;
        padding: 7px 10px;
    }

    .home-product-badge.sold {
        background: #fee2e2;
        color: #991b1b;
    }

    .home-product-placeholder {
        color: #94a3b8;
        font-size: 42px;
    }

    .home-product-body {
        display: flex;
        flex: 1;
        flex-direction: column;
        padding: 18px;
    }

    .home-product-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        color: #64748b;
        font-size: 11px;
        font-weight: 850;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .home-product-meta span {
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .home-product-meta i {
        color: #f59e0b;
    }

    .home-product-body h3 {
        min-height: 48px;
        margin: 8px 0 12px;
        font-size: 17px;
        font-weight: 900;
        line-height: 1.35;
    }

    .home-product-body h3 a {
        display: -webkit-box;
        overflow: hidden;
        color: #111827;
        text-decoration: none;
        overflow-wrap: anywhere;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .home-product-body h3 a:hover {
        color: #2563eb;
    }

    .home-product-foot {
        margin-top: auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
    }

    .home-product-foot strong {
        min-width: 0;
        color: #111827;
        font-size: 20px;
        font-weight: 950;
        white-space: nowrap;
    }

    .home-cart-btn {
        flex: 0 0 auto;
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        border: 0;
        border-radius: 50%;
        background: #111827;
        color: #fff;
        text-decoration: none;
    }

    .home-cart-btn:hover {
        background: #2563eb;
        color: #fff;
    }

    .home-cart-btn.disabled {
        background: #cbd5e1;
        cursor: not-allowed;
    }

    .home-empty {
        border: 1px dashed #cbd5e1;
        border-radius: 18px;
        background: #fff;
        text-align: center;
        padding: 56px 20px;
    }

    .home-empty i {
        color: #94a3b8;
        font-size: 44px;
    }

    .home-promo {
        padding: 84px 0 0;
    }

    .home-promo-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 28px;
        border-radius: 24px;
        background: #111827;
        color: #fff;
        padding: 44px;
    }

    .home-promo h2 {
        max-width: 720px;
        margin: 6px 0 12px;
        font-size: clamp(28px, 4vw, 44px);
        font-weight: 950;
        letter-spacing: 0;
    }

    .home-promo p {
        max-width: 680px;
        margin: 0;
        color: rgba(255, 255, 255, .72);
        line-height: 1.7;
    }

    @media (max-width: 991px) {
        .home-product-image {
            height: 206px;
            padding: 14px;
        }

        .home-hero {
            min-height: auto;
        }

        .home-hero-shade {
            background: linear-gradient(180deg, rgba(15, 23, 42, .94) 0%, rgba(15, 23, 42, .72) 58%, rgba(15, 23, 42, .38) 100%);
        }

        .home-hero-content {
            grid-template-columns: 1fr;
            align-items: start;
            padding-top: 82px;
            padding-bottom: 72px;
        }

        .home-hero-card {
            max-width: 460px;
        }

        .home-promo-inner,
        .home-section-head {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media (max-width: 575px) {
        .home-hero-media img {
            object-position: center;
        }

        .home-hero-content {
            gap: 22px;
            padding-top: 54px;
            padding-bottom: 48px;
        }

        .home-hero h1 {
            font-size: 36px;
            line-height: 1.04;
        }

        .home-hero p {
            margin-top: 16px;
            font-size: 15px;
            line-height: 1.6;
        }

        .home-hero-actions {
            display: grid;
            grid-template-columns: 1fr;
            margin-top: 24px;
        }

        .home-hero-actions .btn,
        .home-promo-inner .btn {
            width: 100%;
        }

        .home-hero-card {
            padding: 20px;
        }

        .home-trust-item {
            align-items: flex-start;
            gap: 10px;
            padding: 14px;
        }

        .home-trust-item i {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            font-size: 17px;
        }

        .home-trust-item strong {
            font-size: 14px;
        }

        .home-trust-item span {
            font-size: 12px;
        }

        .home-trust {
            margin-top: 18px;
        }

        .home-section {
            padding-top: 58px;
        }

        .home-section-head {
            gap: 12px;
            margin-bottom: 20px;
        }

        .home-section h2,
        .home-promo h2 {
            font-size: 28px;
            line-height: 1.15;
        }

        .home-category {
            padding: 18px;
        }

        .home-category-icon {
            margin-bottom: 18px;
        }

        .home-product-image {
            height: 190px;
            padding: 12px;
        }

        .home-product-body h3 {
            min-height: auto;
        }

        .home-promo-inner {
            padding: 28px;
            border-radius: 18px;
        }
    }

    @media (max-width: 380px) {
        .home-hero h1 {
            font-size: 32px;
        }

        .home-product-foot {
            align-items: flex-start;
            flex-direction: column;
        }

        .home-cart-btn {
            width: 100%;
            border-radius: 10px;
        }
    }
</style>
@endsection
