@extends('frontend.layouts.frontend')
@section('content')
<style>
.main { margin: 4%; }
.table-img { max-height: 80px;object-fit: contain;}
</style>

<div class="main py-4">
    <h2 class="mb-4">My Wishlist</h2>
    @if($wishlists->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($wishlists as $item)
                        @if($item->product)
                            <tr>
                                <td>
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="img-fluid table-img">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td>{{ $item->product->name }}</td>
                                <td>
                                    @if($item->product->price)
                                        ₹{{ number_format($item->product->price, 2) }}
                                    @else
                                        <span class="text-muted">No price available</span>
                                    @endif
                                </td>
                                <td>{{ $item->product->shortdescription }}</td>
                                <td>
                                    <form method="POST" action="{{ route('wishlist.addToCartAndRemove', $item->product->id) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-primary mb-1" type="submit">Add to Cart</button>
                                    </form>
                                    <form method="POST" action="{{ route('wishlist.remove', $item->product->id) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-danger" type="submit">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>You have no items in your wishlist.</p>
    @endif
</div>
@endsection
