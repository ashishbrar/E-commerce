<header>
    <nav class="navbar navbar-expand-lg bg-transparent fixed-top">
        <div class="container-fluid d-flex align-items-center">
            <a class="navbar-brand fw-bold fs-4 text-primary" href="{{ route('home') }}">E-commerce</a>

            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 d-flex flex-row gap-3">
                 <li class="nav-item">
                    <a class="btn fw-semibold text-dark text-uppercase" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="btn fw-semibold text-dark text-uppercase" href="{{ route('shop.shop') }}">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="btn fw-semibold text-dark text-uppercase" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="btn fw-semibold text-dark text-uppercase" href="#">Services</a>
                </li>
                <li class="nav-item">
                    <a class="btn fw-semibold text-dark text-uppercase" href="#">Contact Us</a>
                </li>
            </ul>

            <ul class="nav ms-auto d-flex align-items-center gap-3">
                <li class="nav-item position-relative">
                    <a class="btn fw-semibold text-dark text-uppercase d-flex align-items-center gap-2 position-relative" href="{{ route('cart.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#333" viewBox="0 0 24 24">
                            <path d="M7 4h-2l-1 2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2h-11.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h6.72c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.3.12-.47 0-.55-.45-1-1-1h-16zm-1 18c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm14 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/>
                        </svg>
                        @php
                            $cartCount = 0;
                            if(auth()->check()) {
                                $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                            }
                        @endphp
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $cartCount }}
                                <span class="visually-hidden">items in cart</span>
                            </span>
                        @endif
                    </a>
                </li>

                <li class="nav-item position-relative">
                    <a class="btn fw-semibold text-dark text-uppercase d-flex align-items-center position-relative" href="{{ route('wishlist.index') }}">
                        @php
                            $wishlistCount = auth()->check() ? \App\Models\Wishlist::where('user_id', auth()->id())->count() : 0;
                        @endphp
                        @if($wishlistCount > 0)
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#e74c3c" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#e74c3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart" viewBox="0 0 24 24">
                                <path d="M20.8 4.6c-1.5-1.4-3.9-1.4-5.4 0l-.4.4-.4-.4c-1.5-1.4-3.9-1.4-5.4 0-1.6 1.5-1.6 4 0 5.5l5.8 5.7 5.8-5.7c1.6-1.5 1.6-4 0-5.5z"/>
                            </svg>
                        @endif
                        @if($wishlistCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $wishlistCount }}
                            </span>
                        @endif
                    </a>
                </li>
            </ul>

            @if (Route::has('login'))
                <div class="d-flex align-items-center gap-3 ms-3">
                    @auth
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true"><img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" alt="Profile" width="32" height="32" class="rounded-circle"></a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
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
                        <a href="{{ route('login') }}" class="btn btn-success fw-bold text-uppercase">Log in</a>
                    @endauth
                </div>
            @endif
        </div>
    </nav>
</header>
