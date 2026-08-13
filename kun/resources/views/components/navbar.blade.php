<nav class="navbar">
    <div class="container navbar-container">
        <!-- Logo -->
        <div class="navbar-logo">
            <a href="/" class="logo">
                <i class="fas fa-film"></i>
                <span>KUN</span>
            </a>
        </div>
        
        <!-- Navigation Links -->
        <ul class="navbar-menu">
            <li><a href="/" class="nav-link active">Home</a></li>
            <li><a href="/movies" class="nav-link">Movies</a></li>
            <li><a href="/series" class="nav-link">TV Series</a></li>
            <li><a href="/genres" class="nav-link">Genres</a></li>
            <li><a href="/my-list" class="nav-link">My List</a></li>
        </ul>
        
        <!-- Right Side Menu -->
        <div class="navbar-right">
            <!-- Search -->
            <div class="search-container">
                <button class="search-toggle" id="searchToggle">
                    <i class="fas fa-search"></i>
                </button>
                <div class="search-box" id="searchBox">
                    <input type="text" placeholder="Search movies, series..." class="search-input">
                    <button class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            
            <!-- Notifications -->
            <div class="notification-container">
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
            </div>
            
            <!-- User Profile -->
            <div class="profile-container">
                <button class="profile-btn" id="profileBtn">
                    <img src="https://ui-avatars.com/api/?name=User&background=e50914&color=fff" alt="Profile" class="profile-avatar">
                    <i class="fas fa-caret-down"></i>
                </button>
                <div class="profile-dropdown" id="profileDropdown">
                    <a href="/profile" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                    <a href="/account" class="dropdown-item">
                        <i class="fas fa-cog"></i>
                        <span>Account Settings</span>
                    </a>
                    <a href="/subscription" class="dropdown-item">
                        <i class="fas fa-crown"></i>
                        <span>Subscription</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="/logout" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Sign Out</span>
                    </a>
                </div>
            </div>
            
            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/movies">Movies</a></li>
            <li><a href="/series">TV Series</a></li>
            <li><a href="/genres">Genres</a></li>
            <li><a href="/my-list">My List</a></li>
            <li class="divider"></li>
            <li><a href="/profile">Profile</a></li>
            <li><a href="/account">Account Settings</a></li>
            <li><a href="/subscription">Subscription</a></li>
            <li><a href="/logout">Sign Out</a></li>
        </ul>
    </div>
</nav>

<style>
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    background: linear-gradient(180deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 100%);
    transition: all 0.3s ease;
    padding: 0;
}

.navbar.scrolled {
    background: rgba(20, 20, 20, 0.98);
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.navbar-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 20px;
    height: 70px;
}

.navbar-logo .logo {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 28px;
    font-weight: 900;
    color: var(--primary-color);
    text-transform: uppercase;
    letter-spacing: 2px;
}

.navbar-logo .logo i {
    font-size: 32px;
}

.navbar-menu {
    display: flex;
    list-style: none;
    gap: 30px;
    margin: 0;
}

.navbar-menu .nav-link {
    color: var(--text-secondary);
    font-size: 14px;
    font-weight: 500;
    position: relative;
    padding: 5px 0;
}

.navbar-menu .nav-link:hover,
.navbar-menu .nav-link.active {
    color: var(--text-primary);
}

.navbar-menu .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--primary-color);
    border-radius: 2px;
}

.navbar-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

/* Search */
.search-container {
    position: relative;
}

.search-toggle {
    background: none;
    color: var(--text-primary);
    font-size: 18px;
    padding: 8px;
}

.search-box {
    position: absolute;
    right: 0;
    top: 100%;
    margin-top: 10px;
    background: rgba(0, 0, 0, 0.95);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    display: flex;
    align-items: center;
    overflow: hidden;
    width: 0;
    opacity: 0;
    transition: all 0.3s ease;
}

.search-box.active {
    width: 300px;
    opacity: 1;
}

.search-input {
    flex: 1;
    background: none;
    border: none;
    color: var(--text-primary);
    padding: 12px 15px;
    font-size: 14px;
    outline: none;
}

.search-btn {
    background: var(--primary-color);
    color: white;
    padding: 12px 20px;
    border: none;
}

.search-btn:hover {
    background: var(--secondary-color);
}

/* Notifications */
.notification-container {
    position: relative;
}

.notification-btn {
    background: none;
    color: var(--text-primary);
    font-size: 18px;
    padding: 8px;
    position: relative;
}

.notification-badge {
    position: absolute;
    top: 2px;
    right: 2px;
    background: var(--primary-color);
    color: white;
    font-size: 10px;
    font-weight: bold;
    padding: 2px 5px;
    border-radius: 10px;
    min-width: 16px;
    text-align: center;
}

/* Profile */
.profile-container {
    position: relative;
}

.profile-btn {
    background: none;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 4px;
}

.profile-avatar {
    width: 35px;
    height: 35px;
    border-radius: 6px;
    object-fit: cover;
}

.profile-btn i {
    color: var(--text-primary);
    font-size: 12px;
    transition: transform 0.3s ease;
}

.profile-container:hover .profile-btn i {
    transform: rotate(180deg);
}

.profile-dropdown {
    position: absolute;
    right: 0;
    top: calc(100% + 10px);
    background: rgba(0, 0, 0, 0.95);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    min-width: 200px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    padding: 8px 0;
}

.profile-container:hover .profile-dropdown,
.profile-dropdown.active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: var(--text-secondary);
    font-size: 14px;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background: var(--hover-bg);
    color: var(--text-primary);
}

.dropdown-item i {
    width: 18px;
    font-size: 14px;
}

.dropdown-divider {
    height: 1px;
    background: var(--border-color);
    margin: 8px 0;
}

/* Mobile Menu Toggle */
.mobile-menu-toggle {
    display: none;
    background: none;
    color: var(--text-primary);
    font-size: 24px;
    padding: 8px;
}

/* Mobile Menu */
.mobile-menu {
    position: fixed;
    top: 70px;
    left: 0;
    right: 0;
    background: rgba(20, 20, 20, 0.98);
    backdrop-filter: blur(10px);
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.mobile-menu.active {
    max-height: calc(100vh - 70px);
    overflow-y: auto;
}

.mobile-menu ul {
    list-style: none;
    padding: 20px;
}

.mobile-menu li {
    margin-bottom: 5px;
}

.mobile-menu li.divider {
    height: 1px;
    background: var(--border-color);
    margin: 15px 0;
}

.mobile-menu a {
    display: block;
    padding: 12px 15px;
    color: var(--text-secondary);
    font-size: 16px;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.mobile-menu a:hover {
    background: var(--hover-bg);
    color: var(--text-primary);
}

/* Responsive */
@media (max-width: 992px) {
    .navbar-menu {
        gap: 20px;
    }
    
    .navbar-menu .nav-link {
        font-size: 13px;
    }
}

@media (max-width: 768px) {
    .navbar-menu {
        display: none;
    }
    
    .mobile-menu-toggle {
        display: block;
    }
    
    .search-box.active {
        width: 250px;
    }
    
    .navbar-right {
        gap: 10px;
    }
}

@media (max-width: 480px) {
    .navbar-logo .logo {
        font-size: 24px;
    }
    
    .navbar-logo .logo i {
        font-size: 28px;
    }
    
    .search-box.active {
        width: 200px;
    }
    
    .notification-container {
        display: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search toggle
    const searchToggle = document.getElementById('searchToggle');
    const searchBox = document.getElementById('searchBox');
    
    if (searchToggle) {
        searchToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            searchBox.classList.toggle('active');
            if (searchBox.classList.contains('active')) {
                searchBox.querySelector('.search-input').focus();
            }
        });
    }
    
    // Mobile menu toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('active');
            const icon = this.querySelector('i');
            if (mobileMenu.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
    
    // Profile dropdown (mobile)
    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');
    
    if (profileBtn && window.innerWidth <= 768) {
        profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('active');
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (searchBox && !searchBox.contains(e.target) && !searchToggle.contains(e.target)) {
            searchBox.classList.remove('active');
        }
        if (profileDropdown && !profileDropdown.contains(e.target) && !profileBtn.contains(e.target)) {
            profileDropdown.classList.remove('active');
        }
    });
});
</script>
