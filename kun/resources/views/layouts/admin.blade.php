<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - KUN Movie</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary-color: #e50914;
            --secondary-color: #831010;
            --dark-bg: #0a0a0a;
            --darker-bg: #000000;
            --light-bg: #1a1a1a;
            --card-bg: #141414;
            --text-primary: #ffffff;
            --text-secondary: #b3b3b3;
            --text-muted: #808080;
            --border-color: #2a2a2a;
            --hover-bg: #2f2f2f;
            --success-color: #46d369;
            --warning-color: #ffa500;
            --danger-color: #ff4444;
            --info-color: #2196f3;
            --purple-color: #9c27b0;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        /* Scrollbar */
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
        
        /* Layout */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .admin-sidebar {
            width: 260px;
            background: var(--card-bg);
            border-right: 1px solid var(--border-color);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            transition: transform 0.3s ease;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-primary);
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .logo-text h2 {
            font-size: 1.5rem;
            font-weight: 800;
        }
        
        .logo-text p {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
        }
        
        .sidebar-nav {
            padding: 1.5rem 0;
        }
        
        .nav-section {
            margin-bottom: 2rem;
        }
        
        .nav-section-title {
            padding: 0 1.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
        }
        
        .nav-item {
            list-style: none;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover {
            background: var(--hover-bg);
            color: var(--text-primary);
            border-left-color: var(--primary-color);
        }
        
        .nav-link.active {
            background: var(--hover-bg);
            color: var(--text-primary);
            border-left-color: var(--primary-color);
            font-weight: 600;
        }
        
        .nav-link i {
            width: 20px;
            font-size: 1.1rem;
        }
        
        .nav-badge {
            margin-left: auto;
            background: var(--primary-color);
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            font-weight: 600;
        }
        
        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: 260px;
            min-height: 100vh;
        }
        
        /* Top Bar */
        .admin-topbar {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
        }
        
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }
        
        .topbar-search {
            position: relative;
        }
        
        .search-input {
            background: var(--light-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            color: var(--text-primary);
            width: 300px;
            outline: none;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }
        
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .topbar-notification {
            position: relative;
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .topbar-notification:hover {
            background: var(--light-bg);
            color: var(--text-primary);
        }
        
        .notification-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background: var(--danger-color);
            color: white;
            font-size: 0.65rem;
            padding: 0.15rem 0.4rem;
            border-radius: 10px;
            font-weight: 600;
        }
        
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--light-bg);
            padding: 0.5rem 1rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }
        
        .topbar-user:hover {
            background: var(--hover-bg);
            border-color: var(--primary-color);
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }
        
        .user-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .user-info h4 {
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .user-info p {
            font-size: 0.75rem;
            color: var(--text-muted);
        }
        
        /* Content Area */
        .admin-content {
            padding: 2rem;
        }
        
        /* Dashboard Specific Styles */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }
        
        .page-title i {
            color: var(--primary-color);
        }
        
        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
        }
        
        .header-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        
        .btn-refresh,
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }
        
        .btn-refresh {
            background: var(--light-bg);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }
        
        .btn-refresh:hover {
            background: var(--hover-bg);
            border-color: var(--primary-color);
        }
        
        .btn-secondary {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-secondary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            gap: 1rem;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            box-shadow: 0 10px 30px rgba(229, 9, 20, 0.2);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            flex-shrink: 0;
        }
        
        .stat-primary .stat-icon { background: rgba(229, 9, 20, 0.15); color: var(--primary-color); }
        .stat-success .stat-icon { background: rgba(70, 211, 105, 0.15); color: var(--success-color); }
        .stat-info .stat-icon { background: rgba(33, 150, 243, 0.15); color: var(--info-color); }
        .stat-warning .stat-icon { background: rgba(255, 165, 0, 0.15); color: var(--warning-color); }
        .stat-purple .stat-icon { background: rgba(156, 39, 176, 0.15); color: var(--purple-color); }
        .stat-danger .stat-icon { background: rgba(255, 68, 68, 0.15); color: var(--danger-color); }
        
        .stat-content {
            flex: 1;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .stat-meta {
            font-size: 0.8rem;
        }
        
        .stat-change {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-weight: 600;
        }
        
        .stat-change.positive {
            background: rgba(70, 211, 105, 0.15);
            color: var(--success-color);
        }
        
        .stat-change.negative {
            background: rgba(255, 68, 68, 0.15);
            color: var(--danger-color);
        }
        
        /* Charts Row */
        .charts-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .chart-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
        }
        
        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .card-title i {
            color: var(--primary-color);
        }
        
        .card-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .period-select {
            background: var(--light-bg);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
            outline: none;
        }
        
        .btn-link {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: color 0.3s;
        }
        
        .btn-link:hover {
            color: var(--secondary-color);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .card-body canvas {
            max-height: 300px;
        }
        
        /* Content Row */
        .content-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .content-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
        }
        
        /* Tables */
        .table-responsive {
            overflow-x: auto;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table thead tr {
            border-bottom: 1px solid var(--border-color);
        }
        
        .data-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
        }
        
        .data-table tbody tr:hover {
            background: var(--light-bg);
        }
        
        .rank-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            font-weight: 700;
            font-size: 0.9rem;
        }
        
        .rank-1 { background: linear-gradient(135deg, #ffd700, #ffed4e); color: #000; }
        .rank-2 { background: linear-gradient(135deg, #c0c0c0, #e8e8e8); color: #000; }
        .rank-3 { background: linear-gradient(135deg, #cd7f32, #f4a460); color: #000; }
        
        .movie-info-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .movie-thumb {
            width: 50px;
            height: 75px;
            border-radius: 6px;
            object-fit: cover;
        }
        
        .movie-info-cell strong {
            display: block;
            margin-bottom: 0.25rem;
        }
        
        .movie-info-cell small {
            color: var(--text-muted);
            font-size: 0.8rem;
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .badge-info { background: rgba(33, 150, 243, 0.15); color: var(--info-color); }
        .badge-success { background: rgba(70, 211, 105, 0.15); color: var(--success-color); }
        .badge-secondary { background: rgba(128, 128, 128, 0.15); color: var(--text-muted); }
        
        .rating-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            color: #ffd700;
            font-weight: 600;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--light-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-icon:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        /* User List */
        .user-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .user-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--light-bg);
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .user-item:hover {
            background: var(--hover-bg);
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .avatar-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .user-info {
            flex: 1;
        }
        
        .user-name {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .user-email {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }
        
        .user-meta {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-completed { background: rgba(70, 211, 105, 0.15); color: var(--success-color); }
        .status-pending { background: rgba(255, 165, 0, 0.15); color: var(--warning-color); }
        .status-failed { background: rgba(255, 68, 68, 0.15); color: var(--danger-color); }
        
        /* Quick Actions */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }
        
        .quick-action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            padding: 1.5rem 1rem;
            background: var(--light-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-primary);
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .quick-action-btn:hover {
            background: var(--hover-bg);
            border-color: var(--primary-color);
            transform: translateY(-5px);
        }
        
        .quick-action-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .bg-primary { background: var(--primary-color); }
        .bg-success { background: var(--success-color); }
        .bg-info { background: var(--info-color); }
        .bg-warning { background: var(--warning-color); }
        .bg-purple { background: var(--purple-color); }
        .bg-danger { background: var(--danger-color); }
        
        .quick-action-btn span {
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        /* Pagination */
        .pagination-wrap {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-color);
        }
        .admin-pagination {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.4rem;
        }
        .admin-pagination .page-btn {
            min-width: 36px;
            height: 36px;
            padding: 0 0.7rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: var(--light-bg);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            border: 1px solid var(--border-color);
            line-height: 1;
        }
        .admin-pagination a.page-btn:hover {
            border-color: var(--primary-color);
            color: #fff;
        }
        .admin-pagination .page-btn.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: #fff;
        }
        .admin-pagination .page-btn.disabled {
            opacity: 0.4;
            cursor: default;
        }
        
        /* Utilities */
        .text-center { text-align: center; }
        .text-muted { color: var(--text-muted); }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            
            .admin-sidebar.active {
                transform: translateX(0);
            }
            
            .admin-main {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .search-input {
                width: 200px;
            }
        }
        
        @media (max-width: 768px) {
            .admin-content {
                padding: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .charts-row,
            .content-row {
                grid-template-columns: 1fr;
            }
            
            .search-input {
                width: 150px;
            }
            
            .user-info h4 {
                display: none;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
                    <div class="logo-icon">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="logo-text">
                        <h2>KUN</h2>
                        <p>Admin Panel</p>
                    </div>
                </a>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <ul>
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-chart-line"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Content Management</div>
                    <ul>
                        <li class="nav-item">
                            <a href="{{ route('admin.movies.index') }}" class="nav-link {{ request()->routeIs('admin.movies.*') ? 'active' : '' }}">
                                <i class="fas fa-film"></i>
                                <span>Movies</span>
                                <span class="nav-badge">{{ \App\Models\Movie::count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.genres.index') }}" class="nav-link {{ request()->routeIs('admin.genres.*') ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <span>Genres</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">User Management</div>
                    <ul>
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i>
                                <span>Users</span>
                                <span class="nav-badge">{{ \App\Models\User::count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                <i class="fas fa-user-tag"></i>
                                <span>Roles</span>
                                <span class="nav-badge">{{ \App\Models\Role::count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                                <i class="fas fa-shield-alt"></i>
                                <span>Permissions</span>
                                <span class="nav-badge">{{ \App\Models\Permission::count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                                <i class="fas fa-credit-card"></i>
                                <span>Payments</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Settings</div>
                    <ul>
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link" target="_blank">
                                <i class="fas fa-globe"></i>
                                <span>View Website</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="nav-link" style="width: 100%; background: none; border: none; cursor: pointer; text-align: left;">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Bar -->
            <div class="admin-topbar">
                <div class="topbar-left">
                    <button class="menu-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="topbar-search">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" placeholder="Search...">
                    </div>
                </div>
                <div class="topbar-right">
                    <button class="topbar-notification">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">5</span>
                    </button>
                    <div class="topbar-user">
                        <div class="user-avatar">
                            @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                            @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="user-info">
                            <h4>{{ auth()->user()->name }}</h4>
                            <p>Administrator</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="admin-content">
                @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('active');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('adminSidebar');
            const menuToggle = document.querySelector('.menu-toggle');
            
            if (window.innerWidth <= 1024) {
                if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
