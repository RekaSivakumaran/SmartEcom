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
    font-family: Arial;
}

.layout {
    display: flex;
    height: 100vh;
}

/* SIDEBAR */
.sidebar {
    width: 240px;
    background: #fff;
    padding: 20px;
    border-right: 1px solid #e8e8e8;
}

.logo {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 30px;
}

.menu li {
    list-style: none;
    margin-bottom: 10px;
}

.menu a {
    display: flex;
    align-items: center;
    text-decoration: none;
    padding: 10px;
    color: #333;
    border-radius: 6px;
}

.menu a:hover {
    background: #f3f7ff;
}

.icon {
    margin-right: 10px;
}

/* Submenu */
.submenu {
    display: none;
    margin-left: 25px;
}

.arrow {
    margin-left: auto;
    transition: 0.3s;
}

/* ROTATE ARROW WHEN OPEN */
.dropdown.active .arrow {
    transform: rotate(90deg);
     background: #f3f7ff;
    color: #17a2b8;
    font-weight: bold;
}

.dropdown.active .submenu {
    display: block;
    
}

/* CONTENT AREA */
.content {
    flex-grow: 1;
    background: #f5faff;
    display: flex;
    flex-direction: column;
}

/* TOP BAR */
.topbar {
    height: 70px;
    background: #fff;
    display: flex;
    align-items: center;
    padding: 0 25px;
    border-bottom: 1px solid #e8e8e8;
    justify-content: space-between;
}

.search {
    width: 400px;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 25px;
}

.user {
    display: flex;
    align-items: center;
    gap: 15px;
}

.bell {
    position: relative;
    font-size: 18px;
}

.count {
    position: absolute;
    top: -8px;
    right: -10px;
    background: #3b82f6;
    color: white;
    padding: 2px 5px;
    border-radius: 50%;
    font-size: 10px;
}

.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.info h4 {
    margin: 0;
    font-size: 14px;
}

.info p {
    margin: 0;
    font-size: 12px;
    color: gray;
}

/* MAIN CONTENT */
.body-container {
    flex-grow: 1;
}

/* FOOTER */
.footer {
    background: #fff;
    padding: 15px;
    text-align: center;
    font-size: 13px;
    border-top: 1px solid #e8e8e8;
}

.menu a.active {
    background: #f3f7ff;
    color: #17a2b8;
    font-weight: bold;
}


    </style>
<body>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <h2 class="logo">SMartEcom</h2>

        <ul class="menu">
            <li>
    <a href="{{ route('dashboard') }}"
       class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i>&nbsp; Dashboard
    </a>
</li>

<li>
    <a href="{{ route('users.index') }}"
   class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
   <i class="fa-solid fa-user icon"></i>&nbsp; Users
</a>

     
</li>

<li>
  <a href="{{ route('customers.index') }}"
   class="{{ request()->routeIs('customers.index') ? 'active' : '' }}">
    <i class="fa-solid fa-user-group"></i>&nbsp; Customers
</a>

</li>



        <!-- <li><a href="#"><i class="fa-solid fa-house"></i>&nbsp; Dashboard</a></li>      -->
        <!-- <li><a href="#"><i class="fa-solid fa-user icon"></i> User</a></li>            -->
<!-- <li><a href="#"><i class="fa-solid fa-user-group"></i>&nbsp; Customers</a></li> -->
   

            <!-- PRODUCTS DROPDOWN -->
            <li class="dropdown">
                <a class="dropBtn">
                      <i class="fa-solid fa-cart-shopping icon"></i> Products <span class="arrow">▸</span>
                    <!-- <i class="icon">🛒</i> Products <span class="arrow">▸</span> -->
                </a>
                <ul class="submenu">
                    <li>
    <a href="{{ route('categorys.index') }}"
       class="{{ request()->routeIs('categorys.index') ? 'active' : '' }}">
       Main Category
    </a>
</li>

 <li>
    <a href="{{ route('subcategories.index') }}"
       class="{{ request()->routeIs('subcategories.index') ? 'active' : '' }}">
       Sub Category
    </a>
</li>

   <li>
    <a href="{{ route('brand.index') }}"
       class="{{ request()->routeIs('brand.index') ? 'active' : '' }}">
       Brand
    </a>
</li>

 <li>
    <a href="{{ route('products.index') }}"
       class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
       Products
    </a>
</li>
             <!-- <li><a href="#">Category</a></li> -->
                    <!-- <li><a href="#">Brand</a></li> -->
                    <!-- <li><a href="#">Products</a></li> -->

                </ul>
            </li>   
            
    <li>
    <a href="{{ route('orders.index') }}"
       class="{{ request()->routeIs('orders.index') ? 'active' : '' }}">
        <i class="fa-solid fa-receipt icon"></i>&nbsp; Order
    </a>
</li>

            <!-- <li><a href="#"><i class="fa-solid fa-receipt icon"></i> Orders</a></li> -->
            <!-- <li><a href="#"><i class="fa-solid fa-file-invoice icon"></i> Report</a></li> -->
            <li><a href="/admin/logout"><i class="fa-solid fa-arrow-right-from-bracket icon"></i> Log Out</a></li>

        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content">

        <!-- Top bar -->
        <div class="topbar">
            <input type="text" class="search" placeholder="Search here..." />
            <div class="user">
                <span class="bell">🔔 <span class="count">0</span></span>
                <img src="{{ asset('Image/userIMG.jpg') }}" class="avatar" alt="User Avatar" >

                <!-- <img src="https://i.pravatar.cc/40" class="avatar" /> -->
                <div class="info">
                    <h4>Reka</h4>
                    <p>Admin</p>
                </div>
            </div>
        </div>

        <!-- Body content -->
        <div class="body-container">
             @yield('content')
            <!-- empty space similar to screenshot -->
        </div>

        <!-- Footer -->
        <footer class="footer">
            All Rights Reserved.
        </footer>

    </main>
</div>

<script>
    // Sidebar dropdown toggle
document.querySelectorAll(".dropBtn").forEach(button => {
    button.addEventListener("click", () => {
        const parent = button.parentElement;

        parent.classList.toggle("active");
    });
});

</script>
</body>
</html>
