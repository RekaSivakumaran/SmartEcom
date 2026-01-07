@extends('Layout.app')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #f5f7fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* DASHBOARD HEADER */
    .dashboard-header {
        padding: 25px 30px;
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        border-radius: 0 0 20px 20px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(23, 162, 184, 0.3);
    }

    .dashboard-header h1 {
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .dashboard-header p {
        font-size: 14px;
        opacity: 0.9;
    }

    /* CONTENT CARDS */
    .cards {
        padding: 0 30px 25px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
    }

    .card {
        background: #fff;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #17a2b8 0%, #138496 100%);
        transition: width 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,.12);
    }

    .card:hover::before {
        width: 8px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .card h4 {
        font-size: 14px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .icon-blue {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }

    .icon-green {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .icon-orange {
        background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
        color: white;
    }

    .icon-red {
        background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
        color: white;
    }

    .value {
        font-size: 32px;
        margin: 10px 0;
        font-weight: 700;
        color: #1e293b;
    }

    .small {
        font-size: 13px;
        margin-top: 10px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .green {
        color: #16a34a;
    }

    .red {
        color: #dc2626;
    }

    .small::before {
        content: '';
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
    }

    .green::before {
        border-bottom: 6px solid #16a34a;
    }

    .red::before {
        border-top: 6px solid #dc2626;
    }

    /* LARGE CARDS */
    .large-card {
        grid-column: span 2;
        background: #fff;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,.08);
    }

    .large-card h4 {
        font-size: 18px;
        color: #1e293b;
        font-weight: 600;
        margin-bottom: 20px;
    }

    /* CATEGORY LIST */
    .list {
        list-style: none;
        padding: 0;
    }

    .list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 15px;
        color: #475569;
        transition: all 0.3s ease;
    }

    .list li:last-child {
        border-bottom: none;
    }

    .list li:hover {
        padding-left: 10px;
        color: #17a2b8;
    }

    .list li span {
        font-weight: 600;
        color: #1e293b;
    }

    /* PROGRESS BAR */
    .progress-container {
        margin-top: 15px;
    }

    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 14px;
        color: #64748b;
    }

    .progress-bar {
        width: 100%;
        height: 10px;
        background: #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #17a2b8 0%, #138496 100%);
        border-radius: 10px;
        transition: width 1s ease;
        animation: fillProgress 1.5s ease forwards;
    }

    @keyframes fillProgress {
        from { width: 0; }
    }

    /* CHART PLACEHOLDER */
    .chart-placeholder {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #f5f7fa 0%, #e2e8f0 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 14px;
        margin-top: 15px;
    }

    /* RESPONSIVE */
    @media (max-width: 1400px) {
        .cards {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 1100px) {
        .cards {
            grid-template-columns: repeat(2, 1fr);
        }
        .large-card {
            grid-column: span 2;
        }
    }

    @media (max-width: 768px) {
        .dashboard-header {
            padding: 20px;
        }
        
        .dashboard-header h1 {
            font-size: 24px;
        }

        .cards {
            padding: 0 15px 20px;
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .card {
            padding: 20px;
        }

        .large-card {
            grid-column: span 1;
        }

        .value {
            font-size: 28px;
        }
    }

    @media (max-width: 600px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .card-icon {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }
    }
</style>

<!-- Dashboard Header -->
<div class="dashboard-header">
    <h1>Dashboard Overview</h1>
    <p>Welcome back! Here's what's happening with your store today.</p>
</div>

<!-- Dashboard Cards -->
<section class="cards">
    <!-- Sales Card -->
    <div class="card">
        <div class="card-header">
            <h4>Total Sales</h4>
            <div class="card-icon icon-blue">💰</div>
        </div>
        <div class="value">Rs. 983,410</div>
        <p class="small green">+3.34% vs last week</p>
    </div>

    <!-- Orders Card -->
    <div class="card">
        <div class="card-header">
            <h4>Total Orders</h4>
            <div class="card-icon icon-green">📦</div>
        </div>
        <div class="value">58,375</div>
        <p class="small red">-2.89% vs last week</p>
    </div>

    <!-- Visitors Card -->
    <div class="card">
        <div class="card-header">
            <h4>Total Visitors</h4>
            <div class="card-icon icon-orange">👥</div>
        </div>
        <div class="value">237,782</div>
        <p class="small green">+8.02% vs last week</p>
    </div>

    <!-- Revenue Card -->
    <div class="card">
        <div class="card-header">
            <h4>Total Revenue</h4>
            <div class="card-icon icon-red">💳</div>
        </div>
        <div class="value">Rs. 1.2M</div>
        <p class="small green">+12.5% vs last month</p>
    </div>

    <!-- Monthly Target Card -->
    <div class="large-card">
        <h4>Monthly Target Progress</h4>
        <div class="progress-container">
            <div class="progress-label">
                <span>Sales Target</span>
                <span>85% Complete</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 85%;"></div>
            </div>
        </div>
        <div class="progress-container">
            <div class="progress-label">
                <span>Order Target</span>
                <span>72% Complete</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 72%;"></div>
            </div>
        </div>
        <div class="progress-container">
            <div class="progress-label">
                <span>Customer Target</span>
                <span>93% Complete</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 93%;"></div>
            </div>
        </div>
    </div>

    <!-- Top Categories Card -->
    <div class="large-card">
        <h4>Top Performing Categories</h4>
        <ul class="list">
            <li>
                <span>📱 Electronics</span>
                <span>Rs. 1,200,000</span>
            </li>
            <li>
                <span>👔 Fashion</span>
                <span>Rs. 950,000</span>
            </li>
            <li>
                <span>🏠 Home & Kitchen</span>
                <span>Rs. 750,000</span>
            </li>
            <li>
                <span>📚 Books & Media</span>
                <span>Rs. 580,000</span>
            </li>
            <li>
                <span>⚽ Sports & Outdoors</span>
                <span>Rs. 420,000</span>
            </li>
        </ul>
    </div>

    <!-- Recent Activity Card -->
    <div class="large-card">
        <h4>Recent Orders Activity</h4>
        <ul class="list">
            <li>
                <span>🟢 New order from John Doe</span>
                <span>2 mins ago</span>
            </li>
            <li>
                <span>🟡 Order #12453 shipped</span>
                <span>15 mins ago</span>
            </li>
            <li>
                <span>🔵 Payment received - Order #12450</span>
                <span>1 hour ago</span>
            </li>
            <li>
                <span>🟢 New order from Sarah Smith</span>
                <span>2 hours ago</span>
            </li>
            <li>
                <span>🟣 Order #12448 completed</span>
                <span>3 hours ago</span>
            </li>
        </ul>
    </div>

    <!-- Sales Chart Placeholder -->
    <div class="large-card">
        <h4>Sales Analytics</h4>
        <div class="chart-placeholder">
            📊 Chart integration ready (Use Chart.js or similar library)
        </div>
    </div>

</section>

@endsection