@extends('Layout.client')

@section('content')

{{-- Title Box --}}
<div class="all-title-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>My Account</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">My Account</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.account-wrapper { padding: 50px 0; background: #f4f6f9; min-height: 60vh; }

/* Profile Card */
.profile-card { background:#fff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.08); overflow:hidden; margin-bottom:24px; }
.profile-card-header { background:linear-gradient(135deg,#d33b33 0%,#a01010 100%); padding:30px 20px; text-align:center; }
.profile-avatar { width:90px; height:90px; background:rgba(255,255,255,0.25); border-radius:50%; margin:0 auto 12px; display:flex; align-items:center; justify-content:center; border:3px solid rgba(255,255,255,0.6); }
.profile-avatar i { color:#fff; font-size:2.2em; }
.profile-card-header h4 { color:#fff; margin:0 0 4px; font-weight:700; }
.profile-card-header .badge-status { display:inline-block; padding:3px 14px; border-radius:20px; font-size:0.78em; font-weight:600; background:rgba(255,255,255,0.25); color:#fff; border:1px solid rgba(255,255,255,0.5); }
.profile-card-body { padding:20px 24px; }
.profile-info-row { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid #f0f0f0; }
.profile-info-row:last-child { border-bottom:none; }
.profile-info-row .icon-box { width:36px; height:36px; border-radius:8px; background:#fff0f0; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.profile-info-row .icon-box i { color:#d33b33; font-size:0.9em; }
.profile-info-row .info-text { flex:1; }
.profile-info-row .info-text small { color:#aaa; font-size:0.75em; display:block; }
.profile-info-row .info-text span { color:#333; font-size:0.92em; font-weight:500; }

/* Stats */
.stats-row { display:flex; gap:12px; margin-bottom:24px; }
.stat-card { flex:1; background:#fff; border-radius:12px; padding:18px; text-align:center; box-shadow:0 2px 10px rgba(0,0,0,0.06); }
.stat-card .stat-num { font-size:1.8em; font-weight:700; color:#d33b33; }
.stat-card .stat-label { font-size:0.78em; color:#888; margin-top:2px; }

/* Orders Card */
.orders-card { background:#fff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.08); overflow:hidden; }
.orders-card-header { padding:18px 24px; border-bottom:1px solid #f0f0f0; display:flex; align-items:center; justify-content:space-between; }
.orders-card-header h5 { margin:0; font-weight:700; color:#333; }

/* Order Row */
.order-item { border-bottom:1px solid #f5f5f5; }
.order-item:last-child { border-bottom:none; }

.order-summary {
    padding:18px 24px;
    cursor:pointer;
    transition:background 0.2s;
    user-select:none;
}
.order-summary:hover { background:#fafafa; }

.order-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; }
.order-bill { font-weight:700; color:#333; font-size:0.95em; }
.order-date { color:#aaa; font-size:0.78em; margin-top:2px; }
.badge-order { padding:4px 12px; border-radius:20px; font-size:0.75em; font-weight:600; color:#fff; }
.order-products { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px; }
.order-product-img { width:50px; height:50px; border-radius:8px; object-fit:cover; border:2px solid #f0f0f0; }
.order-product-img-placeholder { width:50px; height:50px; border-radius:8px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; }
.order-bottom { display:flex; align-items:center; justify-content:space-between; }
.order-total { font-weight:700; color:#d33b33; font-size:1em; }
.order-items-count { color:#888; font-size:0.8em; }

.chevron-icon { transition:transform 0.3s ease; color:#aaa; font-size:0.8em; margin-left:6px; }
.chevron-icon.open { transform:rotate(180deg); }

.order-expander {
    display:none;
    border-top:2px dashed #f0f0f0;
    padding:20px 24px;
    background:#fafcff;
    animation: slideDown 0.25s ease;
}
@keyframes slideDown {
    from { opacity:0; transform:translateY(-8px); }
    to   { opacity:1; transform:translateY(0); }
}

.address-box { background:#fff; border:1px solid #f0f0f0; border-radius:10px; padding:12px 16px; margin-bottom:16px; }
.address-box .address-label { font-weight:600; color:#555; font-size:0.83em; margin-bottom:4px; }
.address-box .address-text { color:#777; font-size:0.85em; margin:0; }

.items-table { width:100%; border-collapse:collapse; font-size:0.87em; }
.items-table thead tr { background:#f5f5f5; }
.items-table th { padding:9px 12px; color:#555; font-weight:600; text-align:left; }
.items-table th:last-child, .items-table td:last-child { text-align:right; }
.items-table th:nth-child(2), .items-table td:nth-child(2) { text-align:center; }
.items-table th:nth-child(3), .items-table td:nth-child(3) { text-align:right; }
.items-table tbody tr { border-bottom:1px solid #f5f5f5; transition:background 0.15s; }
.items-table tbody tr:hover { background:#fff; }
.items-table tbody tr:last-child { border-bottom:none; }
.items-table td { padding:10px 12px; color:#555; vertical-align:middle; }
.items-table tfoot td { padding:12px; font-weight:700; border-top:2px solid #f0f0f0; }
.product-cell { display:flex; align-items:center; gap:10px; }
.product-cell img { width:40px; height:40px; border-radius:6px; object-fit:cover; border:1px solid #eee; }

.empty-state { text-align:center; padding:50px 20px; }
.empty-state i { font-size:3.5em; color:#ddd; margin-bottom:15px; display:block; }
.empty-state p { color:#aaa; margin-bottom:20px; }
</style>

<div class="account-wrapper">
    <div class="container">

        @php
            $totalOrders    = $orders->count();
            $completedCount = $orders->where('status','Completed')->count();
            $totalSpent     = $orders->where('payment_status','Paid')->sum('total');
        @endphp

        <div class="row">

            {{-- ══ LEFT: Profile ══ --}}
            <div class="col-lg-4 col-md-5 mb-4">

                <div class="profile-card">
                    <div class="profile-card-header">
                        <div class="profile-avatar"><i class="fas fa-user"></i></div>
                        <h4>{{ $customer->name }}</h4>
                        <span class="badge-status">
                            <i class="fas fa-circle" style="font-size:0.5em;vertical-align:middle;"></i>
                            {{ ucfirst($customer->status) }}
                        </span>
                    </div>
                    <div class="profile-card-body">
                        <div class="profile-info-row">
                            <div class="icon-box"><i class="fas fa-envelope"></i></div>
                            <div class="info-text">
                                <small>Email</small>
                                <span>{{ $customer->email }}</span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="icon-box"><i class="fas fa-phone"></i></div>
                            <div class="info-text">
                                <small>Mobile</small>
                                <span>{{ $customer->mobile }}</span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="icon-box"><i class="fas fa-calendar"></i></div>
                            <div class="info-text">
                                <small>Member Since</small>
                                <span>{{ $customer->created_at ? $customer->created_at->format('d M Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-num">{{ $totalOrders }}</div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-num">{{ $completedCount }}</div>
                        <div class="stat-label">Completed</div>
                    </div>
                </div>

                @if($totalSpent > 0)
                <div class="stat-card" style="margin-bottom:24px;">
                    <div class="stat-num">Rs.{{ number_format($totalSpent,2) }}</div>
                    <div class="stat-label">Total Spent</div>
                </div>
                @endif

            </div>

            {{-- ══ RIGHT: Orders ══ --}}
            <div class="col-lg-8 col-md-7">
                <div class="orders-card">
                    <div class="orders-card-header">
                        <h5>
                            <i class="fas fa-shopping-bag" style="color:#d33b33;margin-right:8px;"></i>
                            Order History
                        </h5>
                        <span style="color:#aaa;font-size:0.85em;">{{ $totalOrders }} orders</span>
                    </div>

                    @if($orders->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <p>You haven't placed any orders yet.</p>
                            <a href="{{ route('products.all') }}" class="btn hvr-hover">
                                Start Shopping
                            </a>
                        </div>
                    @else
                        @foreach($orders as $order)
                            @php
                                $statusColors = [
                                    'Pending'    => '#f0ad4e',
                                    'Processing' => '#5bc0de',
                                    'Completed'  => '#28a745',
                                    'Cancelled'  => '#dc3545',
                                ];
                                $payColors = [
                                    'Pending' => '#f0ad4e',
                                    'Paid'    => '#28a745',
                                    'Failed'  => '#dc3545',
                                ];
                            @endphp

                            <div class="order-item">

                                {{-- ── Summary Row (Click to expand) ── --}}
                                <div class="order-summary"
                                     onclick="toggleOrder({{ $order->id }})">

                                    {{-- Top: bill no + badges --}}
                                    <div class="order-top">
                                        <div>
                                            <div class="order-bill">
                                                {{ $order->bill_no }}
                                                <i class="fas fa-chevron-down chevron-icon"
                                                   id="chevron-{{ $order->id }}"></i>
                                            </div>
                                            <div class="order-date">
                                                <i class="fas fa-clock" style="margin-right:4px;"></i>
                                                {{ $order->created_at->format('d M Y, h:i A') }}
                                            </div>
                                        </div>
                                        <div style="display:flex;gap:6px;flex-wrap:wrap;justify-content:flex-end;">
                                            <span class="badge-order"
                                                  style="background:{{ $statusColors[$order->status] ?? '#888' }}">
                                                {{ $order->status }}
                                            </span>
                                            <span class="badge-order"
                                                  style="background:{{ $payColors[$order->payment_status] ?? '#888' }}">
                                                {{ $order->payment_status }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Product image thumbnails --}}
                                    <div class="order-products">
                                        @foreach($order->items->take(4) as $item)
                                            @if($item->image_path)
                                                <img src="{{ asset($item->image_path) }}"
                                                     class="order-product-img"
                                                     title="{{ $item->product->name ?? '' }}">
                                            @else
                                                <div class="order-product-img-placeholder">
                                                    <i class="fas fa-box" style="color:#ccc;"></i>
                                                </div>
                                            @endif
                                        @endforeach
                                        @if($order->items->count() > 4)
                                            <div class="order-product-img-placeholder"
                                                 style="background:#f8f8f8;">
                                                <span style="color:#888;font-size:0.8em;font-weight:600;">
                                                    +{{ $order->items->count() - 4 }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>


                                   @if(session('success'))
    <div class="auto-alert"
         style="background:#d4edda; color:#155724; padding:12px 16px;
                border-radius:8px; margin-bottom:15px; border:1px solid #c3e6cb;">
        ✅ {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="auto-alert"
         style="background:#f8d7da; color:#721c24; padding:12px 16px;
                border-radius:8px; margin-bottom:15px; border:1px solid #f5c6cb;">
        ❌ {{ session('error') }}
    </div>
@endif

                                    
<div class="order-bottom">
    <span class="order-items-count">
        <i class="fas fa-cube" style="margin-right:4px;"></i>
        {{ $order->items->count() }} item(s)
        <small style="color:#bbb;margin-left:6px;">click to view details</small>
    </span>
    <div style="display:flex; align-items:center; gap:10px;">

         
        @if(in_array($order->status, ['Pending', 'Processing']))
            <form method="POST"
                  action="{{ route('order.cancel', $order->id) }}"
                  onsubmit="return confirm('Cancel order {{ $order->bill_no }}?');"
                  onclick="event.stopPropagation();">
                @csrf
                <button type="submit"
                        style="background:#dc3545; color:#fff; border:none;
                               padding:5px 14px; border-radius:20px; font-size:0.78em;
                               font-weight:600; cursor:pointer;">
                    Cancel Order
                </button>
            </form>
        @endif

        <span class="order-total">
            Rs.{{ number_format($order->total, 2) }}
        </span>
    </div>
</div>
                                </div>

                                {{-- ── Expander: Order Details ── --}}
                                <div class="order-expander" id="order-details-{{ $order->id }}">

                                    {{-- Delivery Address --}}
                                    <div class="address-box">
                                        <div class="address-label">
                                            <i class="fas fa-map-marker-alt"
                                               style="color:#d33b33;margin-right:5px;"></i>
                                            Delivery Address
                                        </div>
                                        <p class="address-text">
                                            {{ $order->billing_address1 }}
                                            @if($order->billing_address2)
                                                , {{ $order->billing_address2 }}
                                            @endif
                                            , {{ $order->billing_city }},
                                            {{ $order->billing_country }}
                                            — {{ $order->billing_postcode }}
                                        </p>
                                    </div>

                                    {{-- Items Table --}}
                                    <table class="items-table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Qty</th>
                                                <th>Unit Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->items as $item)
                                                <tr>
                                                    <td>
                                                        <div class="product-cell">
                                                            @if($item->image_path)
                                                                <img src="{{ asset($item->image_path) }}"
                                                                     alt="{{ $item->product->name ?? '' }}">
                                                            @endif
                                                            <span>{{ $item->product->name ?? 'Product' }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>Rs.{{ number_format($item->price, 2) }}</td>
                                                    <td style="color:#d33b33;font-weight:600;">
                                                        Rs.{{ number_format($item->total, 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" style="text-align:right;color:#555;">
                                                    Order Total:
                                                </td>
                                                <td style="color:#d33b33;font-size:1.05em;">
                                                    Rs.{{ number_format($order->total, 2) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                {{-- End Expander --}}

                            </div>
                        @endforeach
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>

<script>

setTimeout(() => {
    document.querySelectorAll('.auto-alert')
        .forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
}, 2000);

function toggleOrder(orderId) {
    const details = document.getElementById('order-details-' + orderId);
    const chevron = document.getElementById('chevron-' + orderId);

    if (details.style.display === 'none' || details.style.display === '') {
        details.style.display = 'block';
        chevron.classList.add('open');
    } else {
        details.style.display = 'none';
        chevron.classList.remove('open');
    }
}
</script>

@endsection