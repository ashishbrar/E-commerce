@php
    $cartCount = 0;
    $wishlistCount = 0;

    if (auth()->check()) {
        $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
        $wishlistCount = \App\Models\Wishlist::where('user_id', auth()->id())->count();
    }
@endphp

<header class="store-header sticky-top">
    <div class="store-topbar">
        <div class="container d-flex flex-wrap align-items-center justify-content-between gap-2">
            <span>Free shipping on prepaid orders over ₹999</span>
            <div class="d-flex align-items-center gap-3">
                <span><i class="bi bi-shield-check me-1"></i>Secure checkout</span>
                <span><i class="bi bi-arrow-repeat me-1"></i>Easy returns</span>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg bg-white">
        <div class="container">
            <a class="navbar-brand store-brand" href="{{ route('home') }}">
                <span class="store-brand-mark">A</span>
                <span>Ace<span>Store</span></span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-3 mb-lg-0 gap-lg-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('shop.shop') ? 'active' : '' }}" href="{{ route('shop.shop') }}">Shop</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#categories">Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#products">New Arrivals</a></li>
                </ul>

                <div class="store-actions">
                    <a href="{{ route('shop.shop') }}" class="store-search d-none d-xl-flex">
                        <i class="bi bi-search"></i>
                        <span>Search products</span>
                    </a>

                    <a href="{{ route('wishlist.index') }}" class="store-icon-btn" aria-label="Wishlist">
                        <i class="bi bi-heart"></i>
                        <span class="store-count bg-danger" data-header-wishlist-count>{{ $wishlistCount }}</span>
                    </a>

                    <a href="{{ route('cart.index') }}" class="store-icon-btn" aria-label="Cart">
                        <i class="bi bi-bag"></i>
                        <span class="store-count bg-primary" data-header-cart-count>{{ $cartCount }}</span>
                    </a>

                    @auth
                        <div class="dropdown">
                            <a href="#" class="store-user dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=111827&color=fff' }}" alt="{{ Auth::user()->name }}">
                                <span class="d-none d-sm-inline">{{ Str::limit(Auth::user()->name, 10) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                <li><a class="dropdown-item py-2" href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger py-2 fw-bold">Log Out</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-dark px-4">Log in</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>

<style>
    .store-header {
        box-shadow: 0 1px 0 rgba(15, 23, 42, .08);
        z-index: 1030;
    }

    .store-topbar {
        background: #0f172a;
        color: rgba(255, 255, 255, .82);
        font-size: 13px;
        font-weight: 650;
        padding: 8px 0;
    }

    .store-brand {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #111827;
        font-size: 24px;
        font-weight: 900;
        letter-spacing: 0;
    }

    .store-brand:hover {
        color: #111827;
    }

    .store-brand span span {
        color: #2563eb;
    }

    .store-brand-mark {
        width: 40px;
        height: 40px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        background: #111827;
        color: #fff;
        font-size: 18px;
    }

    .navbar {
        padding: 14px 0;
    }

    .navbar .nav-link {
        color: #334155;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 800;
        padding: 10px 14px;
    }

    .navbar .nav-link:hover,
    .navbar .nav-link.active {
        background: #f1f5f9;
        color: #111827;
    }

    .store-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .store-search {
        min-width: 190px;
        align-items: center;
        gap: 10px;
        border: 1px solid #e5e7eb;
        border-radius: 999px;
        color: #64748b;
        font-size: 14px;
        font-weight: 700;
        padding: 10px 14px;
        text-decoration: none;
    }

    .store-search:hover,
    .store-icon-btn:hover,
    .store-user:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        color: #111827;
    }

    .store-icon-btn,
    .store-user {
        position: relative;
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e5e7eb;
        border-radius: 999px;
        background: #fff;
        color: #111827;
        text-decoration: none;
    }

    .store-icon-btn i {
        font-size: 19px;
    }

    .store-count {
        position: absolute;
        top: -6px;
        right: -5px;
        min-width: 19px;
        height: 19px;
        display: inline-grid;
        place-items: center;
        border: 2px solid #fff;
        border-radius: 999px;
        color: #fff;
        font-size: 11px;
        font-weight: 850;
        line-height: 1;
    }

    .store-user {
        width: auto;
        gap: 8px;
        padding: 4px 10px 4px 4px;
        font-size: 14px;
        font-weight: 800;
    }

    .store-user img {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        object-fit: cover;
    }

    @media (max-width: 991px) {
        .navbar {
            padding: 10px 0;
        }

        .navbar-collapse {
            border-top: 1px solid #e5e7eb;
            margin-top: 12px;
            padding-top: 12px;
        }

        .navbar .nav-link {
            padding: 11px 12px;
        }

        .store-actions {
            align-items: center;
            flex-wrap: wrap;
            padding-top: 12px;
        }

        .store-topbar .container {
            justify-content: center !important;
            text-align: center;
        }
    }

    @media (max-width: 575px) {
        .store-topbar {
            font-size: 12px;
        }

        .store-topbar .container > div {
            width: 100%;
            justify-content: center;
            gap: 12px !important;
        }

        .store-brand {
            font-size: 20px;
        }

        .store-brand-mark {
            width: 36px;
            height: 36px;
            border-radius: 10px;
        }

        .store-actions {
            display: grid;
            grid-template-columns: repeat(3, minmax(42px, auto));
            justify-content: start;
        }

        .store-actions .btn {
            grid-column: 1 / -1;
            width: 100%;
        }
    }
</style>
