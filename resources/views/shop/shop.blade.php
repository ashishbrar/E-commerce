@extends('frontend.layouts.frontend')
@section('content')
<section class="page-banner">
    <div class="container">
        <span class="section-badge">Shop Catalog</span>
        <h1>Discover products by category and price</h1>
        <p>Use clean filters on mobile or desktop and browse a streamlined responsive catalog.</p>
    </div>
</section>

<div class="container pb-5">
    <div class="row g-4">
        <div class="col-lg-3">
            <form id="filterForm" method="GET" action="{{ url()->current() }}" class="filter-panel sticky-lg-top">
                <div class="filter-card">
                    <h3>Categories</h3>
                    <label class="filter-option">
                        <input type="radio" name="category" value="" onchange="document.getElementById('filterForm').submit()" {{ request('category') ? '' : 'checked' }}>
                        <span>All Categories</span>
                    </label>
                    @foreach($categories as $category)
                        <label class="filter-option">
                            <input type="radio" name="category" value="{{ $category->id }}" onchange="document.getElementById('filterForm').submit()" {{ request('category') == $category->id ? 'checked' : '' }}>
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="filter-card">
                    <h3>Sort by Price</h3>
                    <label class="filter-option">
                        <input type="radio" name="sort" value="" onchange="document.getElementById('filterForm').submit()" {{ !request('sort') ? 'checked' : '' }}>
                        <span>Default</span>
                    </label>
                    <label class="filter-option">
                        <input type="radio" name="sort" value="low_high" onchange="document.getElementById('filterForm').submit()" {{ request('sort') == 'low_high' ? 'checked' : '' }}>
                        <span>Low to High</span>
                    </label>
                    <label class="filter-option">
                        <input type="radio" name="sort" value="high_low" onchange="document.getElementById('filterForm').submit()" {{ request('sort') == 'high_low' ? 'checked' : '' }}>
                        <span>High to Low</span>
                    </label>
                </div>
            </form>
        </div>

        <div class="col-lg-9">
            <div class="section-heading align-items-center">
                <div>
                    <span class="section-badge">Results</span>
                    <h2>{{ $products->total() }} products found</h2>
                </div>
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-sm-6 col-xl-4 d-flex">
                        <article class="product-card flex-fill">
                            <div class="product-media">
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
                                @else
                                    <div class="product-media-placeholder">No Image</div>
                                @endif
                            </div>
                            <div class="product-card-body">
                                <div class="product-meta">{{ $product->category?->name ?? 'General' }}</div>
                                <h3><a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a></h3>
                                <div class="product-price-row">
                                    <span class="product-price">₹{{ number_format($product->price,2) }}</span>
                                </div>
                                <div class="product-actions">
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-dark">View Details</a>
                                    <form action="{{ route('buy.now') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="btn btn-outline-dark w-100">Buy Now</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-search"></i>
                            <h3>No products found</h3>
                            <p>Try switching the category or sort filters to widen the results.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
