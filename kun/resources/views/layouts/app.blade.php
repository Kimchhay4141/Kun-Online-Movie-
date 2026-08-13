<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kun - Your ultimate online movie streaming platform">
    <title>@yield('title', 'Kun - Online Movie Platform')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Base Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary-color: #e50914;
            --secondary-color: #831010;
            --dark-bg: #141414;
            --darker-bg: #0a0a0a;
            --light-bg: #1f1f1f;
            --text-primary: #ffffff;
            --text-secondary: #b3b3b3;
            --text-muted: #808080;
            --border-color: #2a2a2a;
            --hover-bg: #2f2f2f;
            --success-color: #46d369;
            --warning-color: #ffa500;
        }
        
        body {
            font-family: 'Inter', 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        a {
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }
        
        button {
            cursor: pointer;
            border: none;
            outline: none;
            font-family: inherit;
            transition: all 0.3s ease;
        }
        
        img {
            max-width: 100%;
            display: block;
        }
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--darker-bg);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--light-bg);
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--hover-bg);
        }
        
        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Utility Classes */
        .text-primary { color: var(--primary-color); }
        .text-secondary { color: var(--text-secondary); }
        .text-muted { color: var(--text-muted); }
        .mb-1 { margin-bottom: 0.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .mb-3 { margin-bottom: 1.5rem; }
        .mb-4 { margin-bottom: 2rem; }
        .mt-1 { margin-top: 0.5rem; }
        .mt-2 { margin-top: 1rem; }
        .mt-3 { margin-top: 1.5rem; }
        .mt-4 { margin-top: 2rem; }
        
        /* Main Content */
        main {
            min-height: calc(100vh - 80px - 200px);
            padding-top: 80px;
        }
        
        /* Loading Spinner */
        .spinner {
            border: 3px solid var(--border-color);
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 50px auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }
            
            main {
                padding-top: 70px;
            }
        }
    </style>
    
    <!-- Page Specific Styles -->
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    @include('components.navbar')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('components.footer')
    
    <!-- jQuery (for easier DOM manipulation) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Page Specific Scripts -->
    @yield('scripts')
    
    <!-- Global Scripts -->
    <script>
        // Smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Navbar scroll effect
        let lastScroll = 0;
        const navbar = document.querySelector('.navbar');
        
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            lastScroll = currentScroll;
        });
    </script>
</body>
</html>
