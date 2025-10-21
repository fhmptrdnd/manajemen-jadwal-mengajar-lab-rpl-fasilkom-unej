<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAB RPL FASILKOM UNEJ - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1e40af;
            --secondary-color: #3b82f6;
            --light-blue: #dbeafe;
        }

        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-custom {
            background-color: var(--primary-color);
        }

        .sidebar {
            background-color: white;
            min-height: calc(100vh - 56px);
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease-in-out;
        }

        .sidebar .nav-link {
            color: #4b5563;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 10px;
            text-decoration: none;
            display: block;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: var(--light-blue);
            color: var(--primary-color);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }

        .card-custom:hover {
            transform: translateY(-5px);
        }

        .bg-primary-light {
            background-color: var(--light-blue);
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary-custom:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-selesai {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-belum {
            background-color: #fef3c7;
            color: #92400e;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }

        .login-card {
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .login-header {
            background-color: var(--primary-color);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .login-body {
            padding: 30px;
            background-color: white;
        }

        /* mobile sidebar */
        @media (max-width: 767.98px) {
            .sidebar {
                position: fixed;
                top: 56px;
                left: -100%;
                width: 280px;
                height: calc(100vh - 56px);
                z-index: 1000;
                overflow-y: auto;
            }

            .sidebar.show {
                left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 56px;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0,0,0,0.5);
                z-index: 999;
            }

            .sidebar-overlay.show {
                display: block;
            }

            .main-content {
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>
    @if(!request()->is('/') && !request()->is('login'))
        @include('components.navbar')

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 sidebar p-0" id="sidebar">
                    <div class="p-3">
                        <h6 class="text-uppercase text-muted mb-3">Menu</h6>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('pengelolaan') ? 'active' : '' }}" href="{{ route('pengelolaan') }}">
                                    <i class="fas fa-calendar-alt"></i> Jadwal
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
                                    <i class="fas fa-user"></i> Profil
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Main content -->
                <div class="col-md-10 ms-sm-auto px-4 py-4 main-content" id="mainContent">
                    @yield('content')
                </div>
            </div>
        </div>

        @include('components.footer')
    @else
        @yield('content')
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');

            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        // Close sidebar when clicking on overlay
        document.getElementById('sidebarOverlay')?.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking on nav links (mobile)
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    toggleSidebar();
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
