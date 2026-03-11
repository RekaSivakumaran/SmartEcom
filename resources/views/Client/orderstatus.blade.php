@extends('Layout.client')
@section('content')

<style>
.status-wrap {
    min-height: 80vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f4f6f9;
    padding: 30px 20px;
}

.status-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.10);
    width: 100%;
    max-width: 480px;
    overflow: hidden;
    animation: popUp 0.4s ease;
}

@keyframes popUp {
    from { opacity:0; transform: translateY(20px); }
    to   { opacity:1; transform: translateY(0); }
}

/* Header */
.status-header {
    padding: 35px 20px 25px;
    text-align: center;
}

.status-header.success { background: linear-gradient(135deg, #2ecc71, #27ae60); }
.status-header.failed  { background: linear-gradient(135deg, #e74c3c, #c0392b); }

.status-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: rgba(255,255,255,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    border: 3px solid rgba(255,255,255,0.5);
}

.status-icon i { color: #fff; font-size: 1.8em; }

.status-header h2 {
    color: #fff;
    margin: 0;
    font-size: 22px;
    font-weight: 700;
}

.status-header p {
    color: rgba(255,255,255,0.85);
    margin: 6px 0 0;
    font-size: 13px;
}

/* Body */
.status-body { padding: 25px 30px; }

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
    font-size: 14px;
}

.info-row:last-child { border-bottom: none; }

.info-row .label {
    color: #888;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-row .label i { color: #aaa; width: 16px; }

.info-row .value {
    font-weight: 600;
    color: #333;
}

.info-row .value.total { color: #27ae60; font-size: 16px; }

/* Buttons */
.status-footer {
    padding: 0 30px 25px;
    display: flex;
    gap: 10px;
}

.btn-main {
    flex: 1;
    padding: 12px;
    background: #1f77d0;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    transition: 0.2s;
    display: block;
}

.btn-main:hover { background: #155fa0; color: #fff; }

.btn-secondary {
    flex: 1;
    padding: 12px;
    background: #f0f0f0;
    color: #555;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    transition: 0.2s;
    display: block;
}

.btn-secondary:hover { background: #e0e0e0; color: #333; }

/* Failed card */
.failed-body {
    padding: 25px 30px;
    text-align: center;
}

.failed-body p {
    color: #666;
    font-size: 14px;
    margin-bottom: 20px;
}
</style>

<div class="status-wrap">

    @if(session('success'))
    <div class="status-card">

        <!-- Header -->
        <div class="status-header success">
            <div class="status-icon">
                <i class="fas fa-check"></i>
            </div>
            <h2>Order Placed Successfully!</h2>
            <p>Thank you for your purchase 🎉</p>
        </div>

        <!-- Order Details -->
        <div class="status-body">
            <div class="info-row">
                <span class="label"><i class="fas fa-receipt"></i> Bill No</span>
                <span class="value">{{ $order->bill_no }}</span>
            </div>
            <div class="info-row">
                <span class="label"><i class="fas fa-hashtag"></i> Order ID</span>
                <span class="value">#{{ $order->id }}</span>
            </div>
            <div class="info-row">
                <span class="label"><i class="fas fa-box"></i> Status</span>
                <span class="value">
                    <span style="background:#fff3cd; color:#856404; padding:3px 10px;
                                 border-radius:12px; font-size:12px;">
                        {{ $order->status }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="label"><i class="fas fa-credit-card"></i> Payment</span>
                <span class="value">
                    <span style="background:#fff3cd; color:#856404; padding:3px 10px;
                                 border-radius:12px; font-size:12px;">
                        {{ $order->payment_status }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="label"><i class="fas fa-tag"></i> Total</span>
                <span class="value total">Rs. {{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <!-- Buttons -->
        <div class="status-footer">
            <a href="{{ route('client.account') }}" class="btn-secondary">
                <i class="fas fa-list"></i> My Orders
            </a>
            <a href="{{ route('home') }}" class="btn-main">
                <i class="fas fa-shopping-bag"></i> Continue Shopping
            </a>
        </div>

    </div>

    @else
    <div class="status-card">

        <!-- Header -->
        <div class="status-header failed">
            <div class="status-icon">
                <i class="fas fa-times"></i>
            </div>
            <h2>Order Failed!</h2>
            <p>Something went wrong with your order</p>
        </div>

        <!-- Body -->
        <div class="failed-body">
            <p>{{ session('error') ?? 'Please try again or contact support.' }}</p>
        </div>

        <!-- Buttons -->
        <div class="status-footer">
            <a href="{{ route('home') }}" class="btn-secondary">
                <i class="fas fa-home"></i> Go Home
            </a>
            <a href="{{ route('products.all') }}" class="btn-main">
                <i class="fas fa-redo"></i> Try Again
            </a>
        </div>

    </div>
    @endif

</div>

@if(session('success'))
<script>
    localStorage.removeItem("cart");
    const cartCountEl = document.getElementById("cartCount");
    if (cartCountEl) cartCountEl.innerText = "0";
</script>
@endif

@endsection