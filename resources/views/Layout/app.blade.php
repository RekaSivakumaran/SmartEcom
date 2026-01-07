<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SMartEcom</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .layout {
        display: flex;
        height: 100vh;
        background: #f5f7fa;
    }

    /* SIDEBAR */
    .sidebar {
        width: 260px;
        background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
        padding: 0;
        border-right: 1px solid #e3e8ef;
        box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        overflow-y: auto;
        transition: all 0.3s ease;
    }

    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: #17a2b8;
        border-radius: 10px;
    }

    .logo {
        font-size: 24px;
        font-weight: 700;
        padding: 25px 20px;
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        text-align: center;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(23, 162, 184, 0.2);
    }

    .menu {
        padding: 15px 0;
    }

    .menu li {
        list-style: none;
        margin: 0;
    }

    .menu a {
        display: flex;
        align-items: center;
        text-decoration: none;
        padding: 14px 20px;
        color: #495057;
        transition: all 0.3s ease;
        font-size: 15px;
        font-weight: 500;
        position: relative;
    }

    .menu a::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: #17a2b8;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    .menu a:hover {
        background: linear-gradient(90deg, rgba(23, 162, 184, 0.1) 0%, transparent 100%);
        color: #17a2b8;
        padding-left: 25px;
    }

    .menu a:hover::before {
        transform: scaleY(1);
    }

    .menu a.active {
        background: linear-gradient(90deg, rgba(23, 162, 184, 0.15) 0%, rgba(23, 162, 184, 0.05) 100%);
        color: #17a2b8;
        font-weight: 600;
        border-left: 4px solid #17a2b8;
        padding-left: 16px;
    }

    .icon {
        margin-right: 12px;
        font-size: 16px;
        width: 20px;
        text-align: center;
    }

    /* Dropdown Submenu */
    .dropdown {
        position: relative;
    }

    .dropBtn {
        cursor: pointer;
    }

    .submenu {
        display: none;
        background: #f8f9fa;
        padding: 8px 0;
        margin: 5px 0;
        border-left: 2px solid #e3e8ef;
        margin-left: 20px;
    }

    .submenu li a {
        padding: 10px 20px 10px 40px;
        font-size: 14px;
        color: #6c757d;
    }

    .submenu li a:hover {
        color: #17a2b8;
        background: rgba(23, 162, 184, 0.08);
    }

    .submenu li a.active {
        color: #17a2b8;
        background: rgba(23, 162, 184, 0.12);
        border-left: 3px solid #17a2b8;
        padding-left: 37px;
    }

    .arrow {
        margin-left: auto;
        transition: transform 0.3s ease;
        font-size: 12px;
        color: #adb5bd;
    }

    .dropdown.active .arrow {
        transform: rotate(90deg);
    }

    .dropdown.active .submenu {
        display: block;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* CONTENT AREA */
    .content {
        flex-grow: 1;
        background: #f5f7fa;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }

    /* TOP BAR */
    .topbar {
        height: 70px;
        background: #ffffff;
        display: flex;
        align-items: center;
        padding: 0 30px;
        border-bottom: 1px solid #e3e8ef;
        justify-content: space-between;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .search {
        width: 400px;
        padding: 12px 20px 12px 45px;
        border: 2px solid #e3e8ef;
        border-radius: 25px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #f8f9fa url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236c757d' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E") no-repeat 15px center;
    }

    .search:focus {
        outline: none;
        border-color: #17a2b8;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.1);
    }

    .user {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .bell {
        position: relative;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f8f9fa;
    }

    .bell:hover {
        background: #17a2b8;
        transform: scale(1.1);
    }

    .bell:hover .fa-bell {
        color: white;
    }

    .count {
        position: absolute;
        top: 5px;
        right: 5px;
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(220, 53, 69, 0.3);
    }

    .avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        border: 3px solid #17a2b8;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .avatar:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
    }

    .info h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
        color: #212529;
    }

    .info p {
        margin: 0;
        font-size: 13px;
        color: #6c757d;
    }

    /* MAIN CONTENT */
    .body-container {
        flex-grow: 1;
        padding: 0;
    }

    /* FOOTER */
    .footer {
        background: #ffffff;
        padding: 18px;
        text-align: center;
        font-size: 13px;
        border-top: 1px solid #e3e8ef;
        color: #6c757d;
        box-shadow: 0 -2px 8px rgba(0,0,0,0.03);
    }

    /* RESPONSIVE */
    @media (max-width: 1024px) {
        .sidebar {
            width: 220px;
        }
        
        .search {
            width: 300px;
        }
    }

    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            left: -260px;
            height: 100vh;
            z-index: 1000;
            transition: left 0.3s ease;
        }

        .sidebar.show {
            left: 0;
        }

        .search {
            width: 200px;
        }

        .info {
            display: none;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .overlay.show {
            display: block;
        }

        .menu-toggle {
            display: block;
            font-size: 24px;
            cursor: pointer;
            color: #17a2b8;
        }
    }

    .menu-toggle {
        display: none;
    }

    /* Scrollbar styling */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #17a2b8;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #138496;
    }
</style>
<body>

<div class="layout">
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <h2 class="logo">SMartEcom</h2>

        <ul class="menu">
            <li>
                <a href="{{ route('dashboard') }}"
                   class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house icon"></i> Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('users.index') }}"
                   class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-user icon"></i> Users
                </a>
            </li>

            <li>
                <a href="{{ route('customers.index') }}"
                   class="{{ request()->routeIs('customers.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-group icon"></i> Customers
                </a>
            </li>

            <!-- PRODUCTS DROPDOWN -->
            <li class="dropdown">
                <a class="dropBtn">
                    <i class="fa-solid fa-cart-shopping icon"></i> Products 
                    <span class="arrow">▸</span>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="{{ route('categorys.index') }}"
                           class="{{ request()->routeIs('categorys.index') ? 'active' : '' }}">
                           <i class="fa-solid fa-list icon"></i> Main Category
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('subcategories.index') }}"
                           class="{{ request()->routeIs('subcategories.index') ? 'active' : '' }}">
                           <i class="fa-solid fa-layer-group icon"></i> Sub Category
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('brand.index') }}"
                           class="{{ request()->routeIs('brand.index') ? 'active' : '' }}">
                           <i class="fa-solid fa-tag icon"></i> Brand
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}"
                           class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
                           <i class="fa-solid fa-box icon"></i> Products
                        </a>
                    </li>
                </ul>
            </li>   
            
            <li>
                <a href="{{ route('orders.index') }}"
                   class="{{ request()->routeIs('orders.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-receipt icon"></i> Orders
                </a>
            </li>

            <li>
                <a href="/admin/logout">
                    <i class="fa-solid fa-arrow-right-from-bracket icon"></i> Log Out
                </a>
            </li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content">
        <!-- Top bar -->
        <div class="topbar">
            <div style="display: flex; align-items: center; gap: 20px;">
                <span class="menu-toggle" onclick="toggleSidebar()">
                    <i class="fa-solid fa-bars"></i>
                </span>
                <input type="text" class="search" placeholder="Search here..." />
            </div>
            <div class="user">
                <span class="bell">
                    <i class="fa-solid fa-bell"></i>
                    <span class="count">0</span>
                </span>
                <img src="{{ asset('Image/userIMG.jpg') }}" class="avatar" alt="User Avatar">
                <div class="info">
                    @php
                        $admin = \App\Models\UserModel::with('role')->find(session('admin_id'));
                    @endphp
                    <h4>{{ $admin->name ?? 'Admin' }}</h4>
                    <p>{{ $admin->role->name ?? 'Admin' }}</p>
                </div>
            </div>
        </div>

        <!-- Body content -->
        <div class="body-container">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="footer">
            © 2026 SMartEcom. All Rights Reserved.
        </footer>
    </main>
</div>

<!-- Mobile overlay -->
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

<script>
    // Sidebar dropdown toggle
    document.querySelectorAll(".dropBtn").forEach(button => {
        button.addEventListener("click", () => {
            const parent = button.parentElement;
            
            // Close other dropdowns
            document.querySelectorAll(".dropdown").forEach(dropdown => {
                if (dropdown !== parent) {
                    dropdown.classList.remove("active");
                }
            });
            
            parent.classList.toggle("active");
        });
    });

    // Mobile sidebar toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    }

    // Auto-open dropdown if submenu item is active
    document.addEventListener('DOMContentLoaded', function() {
        const activeSubmenuItem = document.querySelector('.submenu a.active');
        if (activeSubmenuItem) {
            const dropdown = activeSubmenuItem.closest('.dropdown');
            if (dropdown) {
                dropdown.classList.add('active');
            }
        }
    });
</script>
</body>
</html>