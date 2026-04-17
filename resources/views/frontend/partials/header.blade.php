@php
    $cartCount = auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') : 0;
    $wishlistCount = auth()->check() ? \App\Models\Wishlist::where('user_id', auth()->id())->count() : 0;
@endphp

<header class="site-header">
    <nav class="navbar navbar-expand-xl site-navbar" data-navbar>
        <div class="container site-nav-shell">
            <a class="site-brand" href="{{ route('home') }}">
                <span class="site-brand-mark"><i class="bi bi-bag-heart"></i></span>
                <span>
                    <strong>Ace Commerce</strong>
                    <small>Curated everyday shopping</small>
                </span>
            </a>

            <button class="navbar-toggler site-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNav" aria-controls="siteNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
            </button>

            <div class="collapse navbar-collapse" id="siteNav">
                <ul class="navbar-nav mx-auto site-links">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('shop.shop') ? 'active' : '' }}" href="{{ route('shop.shop') }}">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="#featured">Featured</a></li>
                    <li class="nav-item"><a class="nav-link" href="#why-ace">Why Us</a></li>
                </ul>

                <div class="site-actions">
                    <a class="site-icon-btn" href="{{ route('cart.index') }}" aria-label="Cart">
                        <i class="bi bi-bag"></i>
                        @if($cartCount > 0)
                            <span class="site-counter">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <a class="site-icon-btn" href="{{ route('wishlist.index') }}" aria-label="Wishlist">
                        <i class="bi bi-heart"></i>
                        @if($wishlistCount > 0)
                            <span class="site-counter">{{ $wishlistCount }}</span>
                        @endif
                    </a>

                    @auth
                        <div class="dropdown">
                            <button class="site-user-toggle dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="site-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                <span class="site-user-meta">
                                    <strong>{{ auth()->user()->name }}</strong>
                                    <small>{{ auth()->user()->email }}</small>
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end site-user-menu">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Log Out</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="site-cta">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>
