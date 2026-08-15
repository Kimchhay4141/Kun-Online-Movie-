<footer class="footer">
    <div class="footer-container">
        <div class="footer-grid">
            <div class="footer-col">
                <div class="footer-logo">
                    <span class="logo-icon"><i class="fas fa-clapperboard"></i></span>
                    <span class="logo-text">KUN</span>
                </div>
                <p class="footer-description">
                    Stream unlimited movies and TV shows online. Watch anywhere, anytime.
                </p>
                <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h3 class="footer-title">Browse</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('movies.index') }}">All Movies</a></li>
                    <li><a href="{{ route('genres.index') }}">Genres</a></li>
                    <li><a href="{{ route('movies.search') }}">Search</a></li>
                    @auth
                    <li><a href="{{ route('favorites.index') }}">My List</a></li>
                    @endauth
                </ul>
            </div>

            <div class="footer-col">
                <h3 class="footer-title">Account</h3>
                <ul class="footer-links">
                    @auth
                        <li><a href="{{ route('profile.show') }}">My Profile</a></li>
                        <li><a href="{{ route('watch.history') }}">Watch History</a></li>
                        <li><a href="{{ route('watchlist.index') }}">Watchlist</a></li>
                    @else
                        <li><a href="{{ route('login') }}">Sign In</a></li>
                        <li><a href="{{ route('register') }}">Sign Up</a></li>
                    @endauth
                </ul>
            </div>

            <div class="footer-col">
                <h3 class="footer-title">Support</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('help') }}">Help Center</a></li>
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                    <li><a href="{{ route('terms') }}">Terms of Service</a></li>
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Kun Online Movie. All rights reserved.</p>
            <p class="footer-credits">Built for movie lovers</p>
        </div>
    </div>
</footer>

<style>
.footer {
    background: #0a0a0a;
    color: #b3b3b3;
    padding: 4rem 0 2rem;
    margin-top: 4rem;
    border-top: 1px solid #1a1a1a;
}

.footer-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
}

.footer-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 3rem;
    margin-bottom: 3rem;
}

.footer-logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.5rem;
    font-weight: 800;
    color: #e50914;
    margin-bottom: 1rem;
}

.logo-icon {
    font-size: 2rem;
}

.footer-description {
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    color: #808080;
}

.social-links {
    display: flex;
    gap: 1rem;
}

.social-links a {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
    color: #b3b3b3;
    font-size: 1.1rem;
    transition: all 0.3s;
}

.social-links a:hover {
    background: #e50914;
    color: #fff;
}

.footer-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #fff;
    margin-bottom: 1rem;
}

.footer-links {
    list-style: none;
}

.footer-links li {
    margin-bottom: 0.75rem;
}

.footer-links a {
    color: #b3b3b3;
    font-size: 0.9rem;
    transition: color 0.3s;
}

.footer-links a:hover {
    color: #fff;
}

.footer-bottom {
    text-align: center;
    padding-top: 2rem;
    border-top: 1px solid #1a1a1a;
    font-size: 0.9rem;
}

.footer-credits {
    margin-top: 0.5rem;
    color: #808080;
}

@media (max-width: 1024px) {
    .footer-grid {
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }
}

@media (max-width: 768px) {
    .footer {
        padding: 3rem 0 1.5rem;
    }
    
    .footer-container {
        padding: 0 1.5rem;
    }
    
    .footer-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
}
</style>
