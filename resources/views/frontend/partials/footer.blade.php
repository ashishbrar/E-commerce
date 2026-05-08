<footer class="store-footer">
    <div class="container">
        <div class="store-footer-cta">
            <div>
                <span class="section-kicker">Stay in the loop</span>
                <h2>Get product drops and offers first.</h2>
            </div>
            <form class="store-newsletter" action="{{ route('shop.shop') }}" method="GET">
                <input type="email" placeholder="Email address" aria-label="Email address">
                <button type="submit">Subscribe</button>
            </form>
        </div>

        <div class="row g-4 store-footer-main">
            <div class="col-lg-4">
                <a href="{{ route('home') }}" class="store-footer-brand">
                    <span>A</span>
                    AceStore
                </a>
                <p>Curated essentials, fresh arrivals, and reliable checkout for everyday shopping.</p>
                <div class="store-socials">
                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <h6>Shop</h6>
                <a href="{{ route('shop.shop') }}">All Products</a>
                <a href="{{ route('home') }}#categories">Categories</a>
                <a href="{{ route('home') }}#products">New Arrivals</a>
            </div>

            <div class="col-6 col-lg-2">
                <h6>Account</h6>
                <a href="{{ route('cart.index') }}">Cart</a>
                <a href="{{ route('wishlist.index') }}">Wishlist</a>
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Log in</a>
                @endauth
            </div>

            <div class="col-lg-4">
                <h6>Support</h6>
                <p class="mb-2">support@ace-ecommerce.com</p>
                <p class="mb-2">+1 234 567 890</p>
                <p class="mb-0">Fast help for orders, returns, and product questions.</p>
            </div>
        </div>

        <div class="store-footer-bottom">
            <span>&copy; {{ date('Y') }} AceStore. All rights reserved.</span>
            <span>Secure payments · Easy returns · Trusted support</span>
        </div>
    </div>
</footer>

<style>
    .store-footer {
        margin-top: 80px;
        background: #0f172a;
        color: rgba(255, 255, 255, .76);
        padding: 44px 0 24px;
    }

    .store-footer-cta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 18px;
        background: rgba(255, 255, 255, .06);
        padding: 28px;
    }

    .store-footer-cta h2 {
        margin: 6px 0 0;
        color: #fff;
        font-size: clamp(24px, 3vw, 34px);
        font-weight: 900;
        letter-spacing: 0;
    }

    .store-newsletter {
        width: min(410px, 100%);
        display: flex;
        gap: 10px;
        border-radius: 12px;
    }

    .store-newsletter input {
        min-width: 0;
        flex: 1;
        border: 1px solid rgba(255, 255, 255, .16);
        border-radius: 12px;
        background: rgba(255, 255, 255, .08);
        color: #fff;
        outline: none;
        padding: 13px 14px;
    }

    .store-newsletter input::placeholder {
        color: rgba(255, 255, 255, .55);
    }

    .store-newsletter button {
        border: 0;
        border-radius: 12px;
        background: #fff;
        color: #111827;
        font-weight: 850;
        padding: 0 18px;
    }

    .store-footer-main {
        padding: 42px 0;
    }

    .store-footer-brand {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        color: #fff;
        font-size: 22px;
        font-weight: 900;
        text-decoration: none;
    }

    .store-footer-brand span {
        width: 38px;
        height: 38px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        background: #2563eb;
    }

    .store-footer p {
        max-width: 360px;
        line-height: 1.7;
    }

    .store-footer h6 {
        margin-bottom: 16px;
        color: #fff;
        font-size: 14px;
        font-weight: 900;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .store-footer a:not(.store-footer-brand) {
        display: block;
        margin-bottom: 10px;
        color: rgba(255, 255, 255, .72);
        text-decoration: none;
        font-weight: 650;
    }

    .store-footer a:hover {
        color: #fff;
    }

    .store-socials {
        display: flex;
        gap: 10px;
    }

    .store-socials a {
        width: 38px;
        height: 38px;
        display: grid !important;
        place-items: center;
        border: 1px solid rgba(255, 255, 255, .14);
        border-radius: 50%;
    }

    .store-footer-bottom {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        border-top: 1px solid rgba(255, 255, 255, .1);
        padding-top: 22px;
        color: rgba(255, 255, 255, .55);
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .store-footer-cta,
        .store-footer-bottom {
            flex-direction: column;
            align-items: flex-start;
        }

        .store-newsletter {
            flex-direction: column;
        }

        .store-newsletter button {
            padding: 13px 18px;
        }
    }
</style>
