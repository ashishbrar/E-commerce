<footer class="site-footer">
    <div class="container">
        <div class="site-footer-grid">
            <div>
                <span class="footer-kicker">Ace Commerce</span>
                <h3>Shopping that feels fast, premium, and reliable.</h3>
                <p>Modern catalog, responsive checkout flow, and an admin workspace built to manage products, users, and orders with confidence.</p>
            </div>
            <div>
                <h5>Explore</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('shop.shop') }}">Shop</a></li>
                    <li><a href="{{ route('cart.index') }}">Cart</a></li>
                    <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
                </ul>
            </div>
            <div>
                <h5>Contact</h5>
                <ul class="list-unstyled">
                    <li>support@ace-ecommerce.com</li>
                    <li>+91 98765 43210</li>
                    <li>Available Monday to Saturday</li>
                </ul>
                <div class="site-socials">
                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>
        </div>
        <div class="site-footer-bottom">
            <span>&copy; {{ date('Y') }} Ace Commerce</span>
            <span>Designed for responsive commerce workflows</span>
        </div>
    </div>
</footer>
