<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <!-- Footer Top -->
            <div class="footer-top">
                <div class="footer-brand">
                    <div class="logo">
                        <i class="fas fa-film"></i>
                        <span>KUN</span>
                    </div>
                    <p class="footer-tagline">Your ultimate destination for unlimited movies and series streaming.</p>
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <div class="footer-column">
                        <h4>Navigation</h4>
                        <ul>
                            <li><a href="/">Home</a></li>
                            <li><a href="/movies">Movies</a></li>
                            <li><a href="/series">TV Series</a></li>
                            <li><a href="/genres">Genres</a></li>
                            <li><a href="/new-releases">New Releases</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h4>Account</h4>
                        <ul>
                            <li><a href="/profile">My Profile</a></li>
                            <li><a href="/my-list">My List</a></li>
                            <li><a href="/account">Account Settings</a></li>
                            <li><a href="/subscription">Subscription</a></li>
                            <li><a href="/billing">Billing</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h4>Support</h4>
                        <ul>
                            <li><a href="/help">Help Center</a></li>
                            <li><a href="/faq">FAQ</a></li>
                            <li><a href="/contact">Contact Us</a></li>
                            <li><a href="/feedback">Feedback</a></li>
                            <li><a href="/terms">Terms of Service</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h4>Company</h4>
                        <ul>
                            <li><a href="/about">About Us</a></li>
                            <li><a href="/careers">Careers</a></li>
                            <li><a href="/press">Press</a></li>
                            <li><a href="/privacy">Privacy Policy</a></li>
                            <li><a href="/cookies">Cookie Preferences</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Footer Divider -->
            <div class="footer-divider"></div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-info">
                    <p>&copy; {{ date('Y') }} Kun. All rights reserved.</p>
                    <p class="footer-location">
                        <i class="fas fa-map-marker-alt"></i>
                        Made with <i class="fas fa-heart" style="color: var(--primary-color);"></i> worldwide
                    </p>
                </div>
                
                <div class="footer-badges">
                    <div class="badge">
                        <i class="fas fa-shield-alt"></i>
                        <span>Secure Payment</span>
                    </div>
                    <div class="badge">
                        <i class="fas fa-star"></i>
                        <span>Premium Quality</span>
                    </div>
                    <div class="badge">
                        <i class="fas fa-headphones"></i>
                        <span>24/7 Support</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.footer {
    background: linear-gradient(180deg, var(--dark-bg) 0%, var(--darker-bg) 100%);
    border-top: 1px solid var(--border-color);
    padding: 60px 0 30px;
    margin-top: 80px;
}

.footer-content {
    width: 100%;
}

.footer-top {
    display: grid;
    grid-template-columns: 2fr 3fr;
    gap: 60px;
    margin-bottom: 40px;
}

.footer-brand {
    max-width: 350px;
}

.footer-brand .logo {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 32px;
    font-weight: 900;
    color: var(--primary-color);
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 20px;
}

.footer-brand .logo i {
    font-size: 36px;
}

.footer-tagline {
    color: var(--text-secondary);
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 25px;
}

.social-links {
    display: flex;
    gap: 15px;
}

.social-link {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--light-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-primary);
    font-size: 16px;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: var(--primary-color);
    transform: translateY(-3px);
}

.footer-links {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 40px;
}

.footer-column h4 {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.footer-column ul {
    list-style: none;
}

.footer-column li {
    margin-bottom: 12px;
}

.footer-column a {
    color: var(--text-secondary);
    font-size: 14px;
    transition: all 0.2s ease;
    display: inline-block;
}

.footer-column a:hover {
    color: var(--text-primary);
    transform: translateX(5px);
}

.footer-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent 0%, var(--border-color) 50%, transparent 100%);
    margin: 40px 0;
}

.footer-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 30px;
}

.footer-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.footer-info p {
    color: var(--text-muted);
    font-size: 13px;
}

.footer-location {
    display: flex;
    align-items: center;
    gap: 8px;
}

.footer-location i {
    font-size: 12px;
}

.footer-badges {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
}

.badge {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-secondary);
    font-size: 13px;
}

.badge i {
    color: var(--primary-color);
    font-size: 16px;
}

/* Responsive */
@media (max-width: 1024px) {
    .footer-top {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    
    .footer-links {
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
    }
}

@media (max-width: 768px) {
    .footer {
        padding: 40px 0 20px;
        margin-top: 60px;
    }
    
    .footer-links {
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
    }
    
    .footer-bottom {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }
    
    .footer-badges {
        width: 100%;
        justify-content: space-between;
        gap: 15px;
    }
    
    .badge {
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .footer-brand .logo {
        font-size: 28px;
    }
    
    .footer-brand .logo i {
        font-size: 32px;
    }
    
    .footer-links {
        grid-template-columns: 1fr;
        gap: 25px;
    }
    
    .footer-badges {
        flex-direction: column;
        gap: 10px;
    }
    
    .social-links {
        justify-content: flex-start;
    }
}
</style>
