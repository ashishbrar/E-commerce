@extends('frontend.layouts.frontend')
@section('content')
<div class="container py-4 mt-5">
    <h2 class="mb-4">Shop</h2>
    <div class="row">
        <div class="col-md-3">
            <form id="filterForm" method="GET" action="{{ url()->current()}}">
                <div class="card mb-4">
                    <div class="card-header">
                        <strong>Categories</strong>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <label>
                                <input type="radio" name="category" value="" 
                                    onchange="document.getElementById('filterForm').submit()"
                                    {{ request('category') ? '' : 'checked' }}> All Categories
                            </label>
                        </li>
                        @foreach($categories as $category)
                        <li class="list-group-item">
                            <label>
                                <input type="radio" name="category" value="{{ $category->id }}"
                                    onchange="document.getElementById('filterForm').submit()"
                                    {{ request('category') == $category->id ? 'checked' : '' }}>
                                {{ $category->name }}
                            </label>    
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <strong>Sort by Price</strong>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <label>
                                <input type="radio" name="sort" value="" 
                                    onchange="document.getElementById('filterForm').submit()"
                                    {{ !request('sort') ? 'checked' : '' }}>
                                Default
                            </label>
                        </li>
                        <li class="list-group-item">
                            <label>
                                <input type="radio" name="sort" value="low_high"
                                    onchange="document.getElementById('filterForm').submit()"
                                    {{ request('sort') == 'low_high' ? 'checked' : '' }}>
                                Low to High
                            </label>
                        </li>
                        <li class="list-group-item">
                            <label>
                                <input type="radio" name="sort" value="high_low"
                                    onchange="document.getElementById('filterForm').submit()"
                                    {{ request('sort') == 'high_low' ? 'checked' : '' }}>
                                High to Low
                            </label>
                        </li>
                    </ul>
                </div>
            </form>
        </div>
        <div class="col-md-9">
            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card h-100">
                            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="card-img-top">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-2" style="font-size:1rem">
                                    <a href="{{ route('products.show', $product->id) }}" class="text-dark text-decoration-none">{{ $product->name }}</a>
                                </h5>
                                <p class="mb-1">
                                    <span class="text-muted">₹{{ number_format($product->price,2) }}</span>
                                </p>
                                @if($product->category)
                                <p class="mb-2 text-secondary" style="font-size:.82rem;">
                                    {{ $product->category->name }}
                                </p>
                                @endif
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-primary mt-auto w-100">
                                    View Details
                                </a>
                                    <form action="{{ route('buy.now') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-warning mt-2 w-100">Buy Now</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center mb-0">
                            No products found.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
