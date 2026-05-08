@extends('frontend.layouts.frontend')

@section('content')
<main class="shop-page">
    <section class="shop-heading">
        <div class="container">
            <span class="section-kicker">Ace Store</span>
            <div class="shop-heading-row">
                <div>
                    <h1>Shop products</h1>
                    <p>Browse products by category, compare prices, and buy only what is available in stock.</p>
                </div>
                <a href="{{ route('home') }}" class="btn btn-outline-dark">
                    <i class="bi bi-arrow-left me-1"></i> Home
                </a>
            </div>
        </div>
    </section>

    <section class="shop-content">
        <div class="container">
            <div class="row g-4 align-items-start">
                <aside class="col-12 col-lg-3">
                    <form id="filterForm" method="GET" action="{{ url()->current() }}" class="shop-filter">
                        <div class="shop-filter-head">
                            <strong>Filters</strong>
                            @if(request('category') || request('sort'))
                                <a href="{{ route('shop.shop') }}">Clear</a>
                            @endif
                        </div>

                        <div class="shop-filter-group">
                            <h2>Categories</h2>
                            <label class="shop-radio">
                                <input type="radio" name="category" value=""
                                    onchange="document.getElementById('filterForm').submit()"
                                    {{ request('category') ? '' : 'checked' }}>
                                <span>All Categories</span>
                            </label>
                            @foreach($categories as $category)
                                <label class="shop-radio">
                                    <input type="radio" name="category" value="{{ $category->id }}"
                                        onchange="document.getElementById('filterForm').submit()"
                                        {{ request('category') == $category->id ? 'checked' : '' }}>
                                    <span>{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="shop-filter-group">
                            <h2>Sort by Price</h2>
                            <label class="shop-radio">
                                <input type="radio" name="sort" value=""
                                    onchange="document.getElementById('filterForm').submit()"
                                    {{ !request('sort') ? 'checked' : '' }}>
                                <span>Default</span>
                            </label>
                            <label class="shop-radio">
                                <input type="radio" name="sort" value="low_high"
                                    onchange="document.getElementById('filterForm').submit()"
                                    {{ request('sort') == 'low_high' ? 'checked' : '' }}>
                                <span>Low to High</span>
                            </label>
                            <label class="shop-radio">
                                <input type="radio" name="sort" value="high_low"
                                    onchange="document.getElementById('filterForm').submit()"
                                    {{ request('sort') == 'high_low' ? 'checked' : '' }}>
                                <span>High to Low</span>
                            </label>
                        </div>
                    </form>
                </aside>

                <div class="col-12 col-lg-9">
                    <div class="shop-result-bar">
                        <strong>{{ $products->total() }} products found</strong>
                        <span>{{ request('sort') ? 'Sorted by selected price order' : 'Latest products first' }}</span>
                    </div>

                    <div class="row g-3 g-md-4">
                        @forelse($products as $product)
                            <div class="col-12 col-sm-6 col-xl-4">
                                <article class="shop-product-card">
                                    <a href="{{ route('products.show', $product->id) }}" class="shop-product-image">
                                        @if($product->quantity > 0)
                                            <span class="shop-stock-badge">In stock</span>
                                        @else
                                            <span class="shop-stock-badge sold">Sold out</span>
                                        @endif

                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                        @else
                                            <div class="shop-product-placeholder">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </a>

                                    <div class="shop-product-body">
                                        <span class="shop-product-category">{{ $product->category->name ?? 'Featured' }}</span>
                                        <h3>
                                            <a href="{{ route('products.show', $product->id) }}">{{ Str::limit($product->name, 54) }}</a>
                                        </h3>
                                        <div class="shop-product-foot">
                                            <strong>₹{{ number_format($product->price, 2) }}</strong>
                                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-dark">Details</a>
                                        </div>

                                        @if($product->quantity > 0)
                                            <form action="{{ route('buy.now') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit" class="btn btn-primary w-100 mt-3">
                                                    <i class="bi bi-lightning-charge me-1"></i> Buy Now
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-secondary w-100 mt-3" disabled>Sold Out</button>
                                        @endif
                                    </div>
                                </article>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="shop-empty">
                                    <i class="bi bi-search"></i>
                                    <h2>No products found.</h2>
                                    <p>Try clearing filters or add products from the dashboard.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if($products->hasPages())
                        <div class="shop-pagination">
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    .shop-page {
        overflow-x: hidden;
        background: #f6f8fb;
    }

    .shop-heading {
        padding: 64px 0 28px;
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
    }

    .shop-heading-row {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 18px;
        margin-top: 8px;
    }

    .shop-heading h1 {
        margin: 0;
        color: #111827;
        font-size: clamp(34px, 5vw, 56px);
        font-weight: 950;
        letter-spacing: 0;
    }

    .shop-heading p {
        max-width: 680px;
        margin: 10px 0 0;
        color: #64748b;
        font-size: 16px;
        line-height: 1.7;
    }

    .shop-content {
        padding: 34px 0 72px;
    }

    .shop-filter,
    .shop-result-bar,
    .shop-product-card,
    .shop-empty {
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 14px 32px rgba(15, 23, 42, .05);
    }

    .shop-filter {
        position: sticky;
        top: 104px;
        overflow: hidden;
    }

    .shop-filter-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #e5e7eb;
        padding: 18px;
    }

    .shop-filter-head strong {
        color: #111827;
        font-size: 18px;
        font-weight: 950;
    }

    .shop-filter-head a {
        color: #2563eb;
        font-size: 13px;
        font-weight: 850;
        text-decoration: none;
    }

    .shop-filter-group {
        padding: 18px;
    }

    .shop-filter-group + .shop-filter-group {
        border-top: 1px solid #e5e7eb;
    }

    .shop-filter-group h2 {
        margin: 0 0 12px;
        color: #334155;
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .shop-radio {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        border-radius: 12px;
        color: #475569;
        cursor: pointer;
        font-weight: 750;
        padding: 10px;
    }

    .shop-radio:hover {
        background: #f8fafc;
        color: #111827;
    }

    .shop-radio input {
        accent-color: #111827;
    }

    .shop-result-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
        padding: 16px 18px;
    }

    .shop-result-bar strong {
        color: #111827;
        font-weight: 950;
    }

    .shop-result-bar span {
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
    }

    .shop-product-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
    }

    .shop-product-card:hover {
        transform: translateY(-3px);
        border-color: #cbd5e1;
        box-shadow: 0 24px 48px rgba(15, 23, 42, .1);
    }

    .shop-product-image {
        position: relative;
        height: 220px;
        display: grid;
        place-items: center;
        background: linear-gradient(180deg, #f8fafc, #eef2f7);
        padding: 16px;
        text-decoration: none;
    }

    .shop-product-image img {
        width: auto;
        height: auto;
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform .22s ease;
    }

    .shop-product-card:hover .shop-product-image img {
        transform: scale(1.04);
    }

    .shop-stock-badge {
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

    .shop-stock-badge.sold {
        background: #fee2e2;
        color: #991b1b;
    }

    .shop-product-placeholder {
        color: #94a3b8;
        font-size: 42px;
    }

    .shop-product-body {
        display: flex;
        flex: 1;
        flex-direction: column;
        padding: 18px;
    }

    .shop-product-category {
        color: #64748b;
        font-size: 12px;
        font-weight: 850;
        text-transform: uppercase;
    }

    .shop-product-body h3 {
        min-height: 46px;
        margin: 10px 0 18px;
        font-size: 17px;
        font-weight: 900;
        line-height: 1.42;
    }

    .shop-product-body h3 a {
        display: -webkit-box;
        overflow: hidden;
        color: #111827;
        text-decoration: none;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .shop-product-body h3 a:hover {
        color: #2563eb;
    }

    .shop-product-foot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: auto;
    }

    .shop-product-foot strong {
        color: #111827;
        font-size: 19px;
        font-weight: 950;
        white-space: nowrap;
    }

    .shop-empty {
        text-align: center;
        padding: 58px 20px;
    }

    .shop-pagination {
        margin-top: 26px;
    }

    .shop-pagination .pagination {
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
    }

    .shop-pagination .page-link {
        border-color: #e5e7eb;
        border-radius: 10px;
        color: #111827;
        font-weight: 800;
    }

    .shop-pagination .page-item.active .page-link {
        border-color: #111827;
        background: #111827;
    }

    .shop-empty i {
        color: #94a3b8;
        font-size: 44px;
    }

    .shop-empty h2 {
        margin: 12px 0 6px;
        color: #111827;
        font-size: 24px;
        font-weight: 950;
    }

    .shop-empty p {
        margin: 0;
        color: #64748b;
    }

    @media (max-width: 991px) {
        .shop-heading {
            padding-top: 42px;
        }

        .shop-heading-row {
            align-items: flex-start;
            flex-direction: column;
        }

        .shop-filter {
            position: static;
        }
    }

    @media (max-width: 575px) {
        .shop-heading {
            padding: 34px 0 22px;
        }

        .shop-heading h1 {
            font-size: 34px;
        }

        .shop-heading .btn,
        .shop-product-foot .btn {
            width: 100%;
        }

        .shop-content {
            padding: 24px 0 52px;
        }

        .shop-result-bar,
        .shop-product-foot {
            align-items: flex-start;
            flex-direction: column;
        }

        .shop-product-body h3 {
            min-height: auto;
        }

        .shop-product-image {
            height: 190px;
            padding: 14px;
        }
    }
</style>
@endsection
