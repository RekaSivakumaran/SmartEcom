@extends('Layout.client')

@section('content')
<style>
    .order-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 80vh;
}

.order-card {
    text-align: center;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    width: 400px;
}

.order-card.success {
    background-color: #e6ffed;
    border: 2px solid #2ecc71;
}

.order-card.failed {
    background-color: #ffe6e6;
    border: 2px solid #e74c3c;
}

.order-card h2 {
    margin-bottom: 15px;
}

.order-card p {
    margin-bottom: 20px;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    background: #2ecc71;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin: 5px;
}

.btn.secondary {
    background: #3498db;
}

.btn:hover {
    opacity: 0.8;
}

</style>

<div class="order-container">
    
    {{-- Success Message --}}
    @if(session('success'))
        <div class="order-card success">
            <h2>🎉 Order Placed Successfully!</h2>
            <p>{{ session('success') }}</p>

            <a href="{{ route('home') }}" class="btn">
                Continue Shopping
            </a>
        </div>
    @endif


    {{-- Failed Message --}}
    @if(session('error'))
        <div class="order-card failed">
            <h2>❌ Order Failed!</h2>
            <p>{{ session('error') }}</p>

            <a href="{{ route('delivery.info') }}" class="btn">
                Try Again
            </a>

            <a href="{{ route('home') }}" class="btn secondary">
                Continue Shopping
            </a>
        </div>
    @endif

</div>

@endsection
