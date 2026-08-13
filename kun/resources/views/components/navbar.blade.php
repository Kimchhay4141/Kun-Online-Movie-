<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <a href="{{ route('home') }}" class="logo">
                <span class="logo-icon">🎬</span>
                <span class="logo-text">KUN</span>
            </a>
        </div>

        <div class="navbar-menu" id="navbarMenu">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="{{ route('movies.index') }}" class="{{ request()->routeIs('movies.*') ? 'active' : '' }}">
                <i class="fas fa-film"></i> Movies
            </a>
            <a href="{{ route('genres.index') }}" class="{{ request()->routeIs('genres.*') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Genres
            </a>
            @auth
            <a href="{{ route('favorites.index') }}" class="{{ request()->routeIs('favorites.*') ? 'active' : '' }}">
                <i class="fas fa-heart"></i> My List
            </a>
            @endauth
        </div>

        <div class="navbar-actions">
            <div class="search-box">
                <form action="{{ route('movies.search') }}" method="GET">
                    <input type="text" name="q" placeholder="Search movies..." class="search-input">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            @auth
                <div class="user-menu">
                    <button class="user-btn" onclick="toggleUserMenu()">
                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="user-avatar">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="user-dropdown" id="userDropdown">
                        <div class="user-info">
                            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                                 alt="{{ auth()->user()->name }}">
                            <div>
                                <p class="user-name">{{ auth()->user()->name }}</p>
                                <p class="user-email">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('profile.show') }}">
                            <i class="fas fa-user"></i> Profile
                        </a>
                        <a href="{{ route('favorites.index') }}">
                            <i class="fas fa-heart"></i> My List
                        </a>
                        <a href="{{ route('watchlist.index') }}">
                            <i class="fas fa-bookmark"></i> Watchlist
                        </a>
                        <a href="{{ route('watch.history') }}">
                            <i class="fas fa-history"></i> History
                        </a>
                        @if(auth()->user()->isAdmin())
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-crown"></i> Admin Panel
                        </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-login">Sign In</a>
                <a href="{{ route('register') }}" class="btn-register">Sign Up</a>
            @endauth

            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</nav>

<style>
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    background: linear-gradient(180deg, rgba(0,0,0,0.9) 0%, transparent 100%);
    transition: all 0.3s ease;
}

.navbar.scrolled {
    background: rgba(0,0,0,0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.navbar-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 1rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
}

.navbar-brand .logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.5rem;
    font-weight: 800;
    color: #e50914;
}

.logo-icon {
    font-size: 2rem;
}

.navbar-menu {
    display: flex;
    gap: 2rem;
    flex: 1;
    margin-left: 2rem;
}

.navbar-menu a {
    color: #e5e5e5;
    font-weight: 500;
    font-size: 0.95rem;
    padding: 0.5rem 0;
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.navbar-menu a:hover,
.navbar-menu a.active {
    color: #fff;
}

.navbar-menu a.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: #e50914;
    border-radius: 2px;
}

.navbar-actions {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.search-box {
    position: relative;
}

.search-box form {
    display: flex;
    align-items: center;
    background: rgba(255,255,255,0.1);
    border-radius: 4px;
    padding: 0.5rem 1rem;
}

.search-input {
    background: transparent;
    border: none;
    color: #fff;
    outline: none;
    width: 200px;
    font-size: 0.9rem;
}

.search-input::placeholder {
    color: rgba(255,255,255,0.5);
}

.search-btn {
    background: transparent;
    color: #fff;
    border: none;
    padding: 0 0 0 0.75rem;
    cursor: pointer;
}

.btn-login,
.btn-register {
    padding: 0.5rem 1.5rem;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.btn-login {
    color: #fff;
}

.btn-login:hover {
    color: #e5e5e5;
}

.btn-register {
    background: #e50914;
    color: #fff;
}

.btn-register:hover {
    background: #f40612;
}

.user-menu {
    position: relative;
}

.user-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: transparent;
    color: #fff;
    padding: 0.25rem;
    border-radius: 4px;
    cursor: pointer;
}

.user-btn:hover {
    background: rgba(255,255,255,0.1);
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 4px;
    object-fit: cover;
}

.user-dropdown {
    position: absolute;
    top: calc(100% + 0.5rem);
    right: 0;
    background: rgba(0,0,0,0.95);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 4px;
    min-width: 220px;
    padding: 0.5rem 0;
    display: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.5);
}

.user-dropdown.active {
    display: block;
}

.user-info {
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-info img {
    width: 40px;
    height: 40px;
    border-radius: 4px;
}

.user-name {
    font-weight: 600;
    font-size: 0.95rem;
}

.user-email {
    font-size: 0.8rem;
    color: #b3b3b3;
    margin-top: 0.25rem;
}

.dropdown-divider {
    height: 1px;
    background: rgba(255,255,255,0.1);
    margin: 0.5rem 0;
}

.user-dropdown a,
.logout-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    color: #e5e5e5;
    font-size: 0.9rem;
    width: 100%;
    text-align: left;
    background: transparent;
}

.user-dropdown a:hover,
.logout-btn:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
}

.mobile-menu-btn {
    display: none;
    background: transparent;
    color: #fff;
    font-size: 1.5rem;
    padding: 0.5rem;
}

@media (max-width: 1024px) {
    .navbar-menu {
        gap: 1.5rem;
    }
    
    .search-input {
        width: 150px;
    }
}

@media (max-width: 768px) {
    .navbar-container {
        padding: 1rem 1.5rem;
    }
    
    .navbar-menu,
    .search-box {
        display: none;
    }
    
    .mobile-menu-btn {
        display: block;
    }
    
    .navbar-menu.mobile-active {
        display: flex;
        flex-direction: column;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.98);
        padding: 1rem 2rem;
        gap: 0;
    }
    
    .navbar-menu.mobile-active a {
        padding: 1rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
}
</style>

<script>
function toggleUserMenu() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('active');
}

function toggleMobileMenu() {
    const menu = document.getElementById('navbarMenu');
    menu.classList.toggle('mobile-active');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const userMenu = document.querySelector('.user-menu');
    const userDropdown = document.getElementById('userDropdown');
    
    if (userMenu && userDropdown && !userMenu.contains(event.target)) {
        userDropdown.classList.remove('active');
    }
});
</script>
