<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Psico Alianza')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="{{ asset('employee.png') }}" type="image/png">
    <style>
        :root {
            --primary-blue: #4339F2;
            --sidebar-bg: #4339F2;
            --sidebar-hover: #3730cc;
            --text-gray: #6c757d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            overflow-x: hidden;
        }

        /* Top Header - Ahora ocupa todo el ancho */
        .top-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            z-index: 1001;
            height: 70px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-section img {
            height: 40px;
            width: auto;
        }

        .sidebar-toggle-header {
            background: rgba(67, 57, 242, 0.1);
            border: none;
            color: var(--primary-blue);
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .sidebar-toggle-header:hover {
            background: rgba(67, 57, 242, 0.2);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 70px;
            left: 0;
            width: 170px;
            height: calc(100vh - 70px);
            background: var(--sidebar-bg);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 60px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 1rem 0;
            margin: 0;
        }

        .sidebar-menu-item {
            position: relative;
        }

        .sidebar-menu-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.95rem;
        }

        .sidebar-menu-link:hover,
        .sidebar-menu-link.active {
            background: var(--sidebar-hover);
            color: white;
        }

        .sidebar-menu-link i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 20px;
        }

        .sidebar.collapsed .sidebar-menu-link span {
            display: none;
        }

        .sidebar-menu-link .arrow {
            margin-left: auto;
            transition: transform 0.3s;
        }

        .sidebar-menu-link.active .arrow {
            transform: rotate(180deg);
        }

        .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            background: rgba(0,0,0,0.1);
            display: none;
        }

        .submenu.show {
            display: block;
        }

        .submenu-link {
            display: block;
            padding: 0.75rem 1.5rem 0.75rem 3.5rem;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .submenu-link:hover,
        .submenu-link.active {
            background: rgba(0,0,0,0.2);
            color: white;
        }

        .sidebar.collapsed .submenu {
            display: none !important;
        }

        /* Main content */
        .main-content {
            margin-left: 170px;
            margin-top: 70px;
            min-height: calc(100vh - 70px);
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 60px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            position: relative;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-blue);
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: var(--primary-blue);
            margin: 0;
            font-size: 0.95rem;
        }

        .user-role {
            color: var(--text-gray);
            font-size: 0.8rem;
            margin: 0;
        }

        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            padding: 0.5rem 0;
            min-width: 200px;
            display: none;
            z-index: 1000;
            margin-top: 0.5rem;
        }

        .user-dropdown.show {
            display: block;
        }

        .dropdown-item {
            padding: 0.75rem 1.25rem;
            color: #333;
            text-decoration: none;
            display: block;
            transition: all 0.3s;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
            color: var(--primary-blue);
        }

        /* Welcome section */
        .welcome-section {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            margin: 2rem;
            border-radius: 12px;
        }

        .welcome-title {
            font-size: 2rem;
            font-weight: 300;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .welcome-name {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 2rem;
        }

        .welcome-description {
            color: var(--text-gray);
            font-size: 1rem;
            margin-bottom: 3rem;
        }

        .start-button {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            background: white;
            border: 2px solid #e0e0e0;
            padding: 1.5rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }

        .start-button:hover {
            border-color: var(--primary-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 57, 242, 0.2);
        }

        .start-icon {
            font-size: 2rem;
            color: var(--primary-blue);
        }

        .start-text {
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        .welcome-illustration {
            max-width: 500px;
            margin: 3rem auto 0;
        }

        .welcome-illustration img {
            width: 100%;
            height: auto;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }

            .sidebar.collapsed {
                width: 0;
                display: none;
            }

            .main-content {
                margin-left: 60px;
            }

            .main-content.expanded {
                margin-left: 0;
            }

            .user-info {
                display: none;
            }

            .logo-section img {
                height: 32px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
<!-- Top Header -->
<div class="top-header">
    <div class="header-left">
        <button class="sidebar-toggle-header" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="logo-section">
            <img src="{{ asset('images/logo-psico-alianza.png') }}" alt="Psico Alianza">
        </div>
    </div>

    <div class="user-menu" onclick="toggleUserDropdown()">
        <div class="user-info">
            <p class="user-name">Elisa Gómez</p>
            <p class="user-role">Administradora</p>
        </div>
        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='50' fill='%234339F2'/%3E%3Ctext x='50' y='65' font-family='Arial' font-size='40' fill='white' text-anchor='middle' font-weight='bold'%3EEG%3C/text%3E%3C/svg%3E" alt="Elisa Gómez" class="user-avatar">
        <div class="user-dropdown" id="userDropdown">
            <a href="#" class="dropdown-item">Perfil</a>
            <a href="#" class="dropdown-item">Configuraciones</a>
            <a href="#" class="dropdown-item">Soporte</a>
            <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Salir</a>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        <li class="sidebar-menu-item">
            <a href="{{ route('home') }}" class="sidebar-menu-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="#" class="sidebar-menu-link {{ request()->routeIs('employees.*') || request()->routeIs('positions.*') ? 'active' : '' }}" onclick="toggleSubmenu(event)">
                <i class="fas fa-list"></i>
                <span>Listas</span>
                <i class="fas fa-chevron-down arrow"></i>
            </a>
            <ul class="submenu {{ request()->routeIs('employees.*') || request()->routeIs('positions.*') ? 'show' : '' }}">
                <li>
                    <a href="{{ route('employees.index') }}" class="submenu-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                        Empleados
                    </a>
                </li>
                <li>
                    <a href="{{ route('positions.index') }}" class="submenu-link {{ request()->routeIs('positions.*') ? 'active' : '' }}">Cargos</a>
                </li>
            </ul>
        </li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Page Content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('collapsed');
        document.getElementById('mainContent').classList.toggle('expanded');
    }

    function toggleSubmenu(event) {
        event.preventDefault();
        const link = event.currentTarget;
        const submenu = link.nextElementSibling;
        const sidebar = document.getElementById('sidebar');

        // No permitir abrir submenú si el sidebar está colapsado
        if (sidebar.classList.contains('collapsed')) {
            return;
        }

        submenu.classList.toggle('show');
        link.classList.toggle('active');
    }

    function toggleUserDropdown() {
        document.getElementById('userDropdown').classList.toggle('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const userMenu = event.target.closest('.user-menu');
        if (!userMenu) {
            document.getElementById('userDropdown').classList.remove('show');
        }
    });
</script>
@stack('scripts')
</body>
</html>
