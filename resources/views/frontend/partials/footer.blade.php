<footer class="bg-dark text-white pt-4 pb-2 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <h5>Ace Ecommerce</h5>
                <p>Your one-stop shop for all your needs. Quality products, great prices, and fast delivery.</p>
            </div>
            <div class="col-md-4 mb-3">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ url('/') }}" class="text-white text-decoration-none">Home</a></li>
                    <li><a href="{{ url('/shop') }}" class="text-white text-decoration-none">Shop</a></li>
                    <li><a href="{{ url('/about') }}" class="text-white text-decoration-none">About Us</a></li>
                    <li><a href="{{ url('/contact') }}" class="text-white text-decoration-none">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-3">
                <h5>Contact Us</h5>
                <ul class="list-unstyled">
                    <li>Email: support@ace-ecommerce.com</li>
                    <li>Phone: +1 234 567 890</li>
                    <li>Address: 123 Main St, City, Country</li>
                </ul>
                <div class="mt-2">
                    <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white me-2"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        </div>
        <hr class="bg-secondary">
        <div class="text-center">
            &copy; {{ date('Y') }} Ace Ecommerce.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
