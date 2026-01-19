<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BEM Management System')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f8fafc;
        }
        
        /* Sidebar styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            z-index: 1000;
            transition: transform 0.3s ease;
            transform: translateX(-100%);
        }
        
        .sidebar.active {
            transform: translateX(0);
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }
        
        .sidebar-logo {
            padding: 0 2rem;
            margin-bottom: 2rem;
            font-size: 1.5rem;
            font-weight: 700;
            text-center;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 2rem;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin: 0;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 1rem 2rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .sidebar-menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: white;
        }
        
        .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: white;
            font-weight: 600;
        }
        
        .sidebar-menu i {
            width: 25px;
            margin-right: 1rem;
            font-size: 1.1rem;
        }
        
        /* Main content styles */
        .main-content {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Hamburger Menu */
        .hamburger-btn {
            background: none;
            border: none;
            color: #667eea;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .hamburger-btn:hover {
            color: #764ba2;
        }
        
        /* Overlay untuk sidebar */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .navbar-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
        }
        
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .user-profile:hover {
            background-color: #f3f4f6;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .user-name {
            color: #374151;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .content-wrapper {
            flex: 1;
            padding: 2rem;
        }
        
        /* Card styles */
        .card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }
        
        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 0.75rem 0.75rem 0 0;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Dashboard cards */
        .dashboard-card {
            background: white;
            border-radius: 0.75rem;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 1rem;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }
        
        .dashboard-card-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .dashboard-card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937;
        }
        
        .dashboard-card-desc {
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        /* Button styles */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }
        
        .btn-danger {
            background: #ef4444;
            color: white;
        }
        
        .btn-danger:hover {
            background: #dc2626;
        }
        
        /* Form styles */
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
            font-size: 0.9rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        /* Alert styles */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background-color: #d1fae5;
            border: 1px solid #6ee7b7;
            color: #065f46;
        }
        
        .alert-danger {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            color: #7f1d1d;
        }
        
        .alert-warning {
            background-color: #fef3c7;
            border: 1px solid #fde68a;
            color: #78350f;
        }
        
        .alert-info {
            background-color: #dbeafe;
            border: 1px solid #93c5fd;
            color: #0c2340;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
            }
            
            .navbar-title {
                font-size: 1.2rem;
            }
            
            .content-wrapper {
                padding: 1rem;
            }
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background-color: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background-color: #d1d5db;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-input.border-red-500 {
            border-color: #ef4444;
        }
    </style>
    
    @yield('css')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <i class="fas fa-cube"></i> BEM
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ Route::is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('surat') }}" class="{{ Route::is('surat') ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i>
                    <span>Surat Masuk/Keluar</span>
                </a>
            </li>
            <li>
                <a href="{{ route('jadwal') }}" class="{{ Route::is('jadwal') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Jadwal</span>
                </a>
            </li>
            <li>
                <a href="{{ route('anggaran') }}" class="{{ Route::is('anggaran') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Anggaran</span>
                </a>
            </li>
            <li>
                <a href="{{ route('rkt') }}" class="{{ Route::is('rkt') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    <span>RKT BEM</span>
                </a>
            </li>
            @if(Auth::user()->role === 'admin')
            <li style="margin-top: 2rem; border-top: 1px solid rgba(255, 255, 255, 0.2); padding-top: 1rem;">
                <a href="{{ route('users.index') }}" class="{{ Route::is('users.index') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Manajemen User</span>
                </a>
            </li>
            @endif
        </ul>
        
        <div style="padding: 2rem; margin-top: auto; border-top: 1px solid rgba(255, 255, 255, 0.2);">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: rgba(255, 255, 255, 0.7); display: flex; align-items: center; gap: 0.75rem; text-decoration: none;">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <div class="navbar">
            <div class="navbar-left">
                <button class="hamburger-btn" id="hamburgerBtn">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="navbar-title">@yield('page-title', 'Dashboard')</div>
            </div>
            <div class="navbar-right">
                <div class="user-profile">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'User', 0, 1)) }}
                    </div>
                    <div class="user-name">{{ Auth::user()->name ?? 'User' }}</div>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content-wrapper">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Ups! Ada kesalahan</strong>
                    <ul style="margin: 0.5rem 0 0 1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    <script>
        // Hamburger menu toggle
        const sidebar = document.getElementById('sidebar');
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        // Toggle sidebar
        hamburgerBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });
        
        // Close sidebar when clicking overlay
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });
        
        // Close sidebar when clicking menu item
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            link.addEventListener('click', () => {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            });
        });
    </script>
    
    @yield('js')
</body>
</html>
