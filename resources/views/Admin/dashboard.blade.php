@extends('Layout.app')


@section('content')
<style>
    /* CONTENT CARDS */
.cards {
    padding: 25px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.card {
    background: #fff;
    padding: 18px;
    border-radius: 14px;
    box-shadow: 0 4px 15px rgba(0,0,0,.05);
}

.value {
    font-size: 28px;
    margin-top: 5px;
    font-weight: bold;
}

.small {
    font-size: 12px;
    margin-top: 8px;
    color: #666;
}

/* RESPONSIVE */
@media (max-width: 1100px) {
    .header input { width: 250px; }
    .cards { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
    .sidebar { width: 180px; }
    .main { margin-left: 180px; }
    .header input { width: 100%; }
    .cards { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 600px) {
    .sidebar { position: absolute; left: -230px; transition: 0.3s; }
    .sidebar.show { left: 0; }
    .header::before {
        content: "☰";
        font-size: 26px;
        cursor: pointer;
        margin-right: 10px;
    }
    .main { margin-left: 0; }
    .cards { grid-template-columns: 1fr; }
}

    </style>

<section class="cards">

    <div class="card">
        <h4>Total Sales</h4>
        <div class="value">$983,410</div>
        <p class="small green">+3.34% vs last week</p>
    </div>

    <div class="card">
        <h4>Total Orders</h4>
        <div class="value">58,375</div>
        <p class="small red">-2.89% vs last week</p>
    </div>

    <div class="card">
        <h4>Total Visitors</h4>
        <div class="value">237,782</div>
        <p class="small green">+8.02% vs last week</p>
    </div>

    <div class="card">
        <h4>Monthly Target</h4>
        <p class="value">85%</p>
        <p class="small green">+8.02% from last month</p>
    </div>

    <div class="card">
        <h4>Top Categories</h4>
        <ul class="list">
            <li>Electronics <span>RS.1,200,000</span></li>
            <li>Fashion <span>Rs.950,000</span></li>
            <li>Home & Kitchen <span>Rs.750,000</span></li>
        </ul>
    </div>

</section>
@endsection

