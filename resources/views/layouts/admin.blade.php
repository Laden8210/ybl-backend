@php
    $navItems = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'bi-speedometer2'],
        ['label' => 'Trips', 'route' => 'admin.trips.index', 'icon' => 'bi-map'],
        ['label' => 'Profile', 'route' => 'admin.profile', 'icon' => 'bi-person'],
        ['label' => 'Buses', 'route' => 'admin.buses.index', 'icon' => 'bi-bus-front'],
        ['label' => 'Staff', 'route' => 'admin.staff.index', 'icon' => 'bi-people'],
        ['label' => 'Assignments', 'route' => 'admin.assignments.index', 'icon' => 'bi-diagram-3'],
        ['label' => 'Routes', 'route' => 'admin.routes.index', 'icon' => 'bi-signpost'],
        ['label'=> 'Schedule', 'route' => 'admin.schedules.index', 'icon' => 'bi-calendar3'],
        ['label' => 'Logs', 'route' => 'admin.logs.index', 'icon' => 'bi-journal-text'],
        ['label' => 'Tracking', 'route' => 'admin.tracking.index', 'icon' => 'bi-geo-alt'],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') • {{ config('app.name', 'Yellow Bus Line') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <style>
        :root {
            --deep-navy: #1E3A5F;
            --primary-yellow: #FFD54F;
            --medium-gray: #E2E8F0;
            --light-gray: #F7FAFC;
        }

        body {
            overflow-x: hidden;
        }

        .sidebar {
            width: 260px;
            background: var(--deep-navy);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link {
            color: #ffffffcc;
            border-radius: 0.375rem;
            margin: 0.125rem 0.5rem;
            padding: 0.625rem 0.75rem;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
        }

        .sidebar .nav-link .bi {
            width: 1.25rem;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid var(--medium-gray);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .content {
            background: var(--light-gray);
            min-height: 100vh;
            margin-left: 260px;
            transition: margin-left 0.3s ease;
        }

        .brand {
            color: var(--primary-yellow);
        }

        /* Mobile styles */
        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        /* Scrollbar styling for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Ensure sidebar content is scrollable if needed */
        .sidebar-content {
            height: 100vh;
            overflow-y: auto;
            padding-bottom: 2rem;
        }
    </style>
    @stack('head')
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-content">
                <div class="p-3">
                    <a href="{{ route('admin.dashboard') }}"
                        class="navbar-brand d-flex align-items-center gap-2 text-white mb-3">
                        <i class="bi bi-bus-front-fill brand"></i>
                        <span class="fw-bold">Admin</span>
                    </a>
                    <hr class="border-secondary">
                    <ul class="nav nav-pills flex-column gap-1">
                        @foreach ($navItems as $item)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}"
                                    href="{{ route($item['route']) }}">
                                    <i class="bi {{ $item['icon'] }} me-2"></i>{{ $item['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main -->
        <div class="flex-grow-1">
            <!-- Topbar -->
            <div class="topbar py-2 px-3 d-flex justify-content-between align-items-center">
                <button class="btn btn-outline-primary btn-sm d-md-none" id="mobileNavToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="fw-semibold">@yield('page_title', 'Dashboard')</div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-secondary small">Admin</span>
                    <a href="/" class="btn btn-sm btn-primary">Public Site</a>
                </div>
            </div>

            <!-- Content -->
            <main class="content p-3 p-lg-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile offcanvas nav (keeping as backup) -->
    <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="mobileNav">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">YBL Admin</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav nav-pills flex-column gap-1">
                @foreach ($navItems as $item)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}"
                            href="{{ route($item['route']) }}">
                            <i class="bi {{ $item['icon'] }} me-2"></i>{{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileNavToggle = document.getElementById('mobileNavToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (mobileNavToggle && sidebar) {
                mobileNavToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                });

                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                });
            }

            // Close sidebar when clicking on a link (mobile)
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
