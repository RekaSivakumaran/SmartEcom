@extends('Layout.app')
@section('content')

<style>
.inv-wrap {
    padding: 15px;
}

.inv-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.inv-header h2 {
    font-size: 24px;
    color: #333;
    margin: 0;
}

/* Alert */
.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 15px;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 15px;
    border: 1px solid #f5c6cb;
}

/* Cards Row */
.stats-row {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.stat-card {
    flex: 1;
    min-width: 160px;
    background: #fff;
    border-radius: 10px;
    padding: 18px 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    text-align: center;
}

.stat-card .stat-num {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 4px;
}

.stat-card .stat-label {
    font-size: 13px;
    color: #888;
}

.stat-card.total  .stat-num { color: #1f77d0; }
.stat-card.low    .stat-num { color: #e67e22; }
.stat-card.out    .stat-num { color: #e74c3c; }
.stat-card.in     .stat-num { color: #27ae60; }

/* Two-column layout */
.inv-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 25px;
}

@media(max-width: 900px) {
    .inv-grid { grid-template-columns: 1fr; }
}

/* Section Box */
.section-box {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    padding: 20px;
}

.section-box h3 {
    font-size: 16px;
    color: #333;
    margin: 0 0 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #17a2b8;
}

/* Form */
.form-group {
    margin-bottom: 12px;
}

.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #555;
    margin-bottom: 5px;
}

.form-group select,
.form-group input,
.form-group textarea {
    width: 100%;
    padding: 9px 12px;
    border: 1px solid #ddd;
    border-radius: 7px;
    font-size: 14px;
    background: #f8fafc;
    box-sizing: border-box;
    transition: 0.2s;
}

.form-group select:focus,
.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #17a2b8;
    background: #fff;
}

.type-toggle {
    display: flex;
    gap: 10px;
    margin-bottom: 12px;
}

.type-btn {
    flex: 1;
    padding: 10px;
    border: 2px solid #ddd;
    border-radius: 8px;
    background: #f8f9fa;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    transition: 0.2s;
    text-align: center;
}

.type-btn.in.active  { background: #27ae60; color: #fff; border-color: #27ae60; }
.type-btn.out.active { background: #e74c3c; color: #fff; border-color: #e74c3c; }
.type-btn:not(.active) { color: #666; }

.submit-btn {
    width: 100%;
    padding: 11px;
    background: #1f77d0;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    margin-top: 5px;
}

.submit-btn:hover { background: #155fa0; }

/* Low Stock Table */
.inv-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.inv-table th {
    background: #17a2b8;
    color: #fff;
    padding: 10px 12px;
    text-align: left;
    font-size: 13px;
}

.inv-table td {
    padding: 10px 12px;
    border-bottom: 1px solid #f0f0f0;
    color: #444;
}

.inv-table tr:hover td { background: #f0faff; }

.badge-low  { background: #fff3cd; color: #856404; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.badge-out  { background: #f8d7da; color: #721c24; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.badge-ok   { background: #d4edda; color: #155724; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: 600; }

/* History Log */
.log-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.log-table th {
    background: #6c757d;
    color: #fff;
    padding: 9px 12px;
    text-align: left;
    font-size: 12px;
    text-transform: uppercase;
}

.log-table td {
    padding: 9px 12px;
    border-bottom: 1px solid #f0f0f0;
}

.log-table tr:hover td { background: #f8f9fa; }

.badge-in  { background: #d4edda; color: #155724; padding: 2px 8px; border-radius: 10px; font-size: 12px; font-weight: 600; }
.badge-out-log { background: #f8d7da; color: #721c24; padding: 2px 8px; border-radius: 10px; font-size: 12px; font-weight: 600; }

.full-width { grid-column: 1 / -1; }
</style>

<div class="inv-wrap">

    <div class="inv-header">
        <h2>📦 Inventory Management</h2>
    </div>

    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">❌ {{ session('error') }}</div>
    @endif

    <!-- Stats Cards -->
    <div class="stats-row">
        <div class="stat-card total">
            <div class="stat-num">{{ $products->count() }}</div>
            <div class="stat-label">Total Products</div>
        </div>
        <div class="stat-card out">
            <div class="stat-num">{{ $products->where('quantity', 0)->count() }}</div>
            <div class="stat-label">Out of Stock</div>
        </div>
        <div class="stat-card low">
            <div class="stat-num">{{ $products->where('quantity', '>', 0)->where('quantity', '<=', 10)->count() }}</div>
            <div class="stat-label">Low Stock (≤10)</div>
        </div>
        <div class="stat-card in">
            <div class="stat-num">{{ $products->where('quantity', '>', 10)->count() }}</div>
            <div class="stat-label">In Stock</div>
        </div>
    </div>

    <div class="inv-grid">

        <!-- Stock Update Form -->
        <div class="section-box">
            <h3>🔄 Update Stock</h3>

            <form method="POST" action="{{ route('inventory.update') }}">
                @csrf

                <!-- Stock In / Out Toggle -->
                <div class="type-toggle">
                    <div class="type-btn in active" id="btn-in" onclick="setType('in')">
                        ⬆ Stock In
                    </div>
                    <div class="type-btn out" id="btn-out" onclick="setType('out')">
                        ⬇ Stock Out
                    </div>
                </div>
                <input type="hidden" name="type" id="type-input" value="in">

                <div class="form-group">
                    <label>Product</label>
                    <select name="product_id" required>
                        <option value="">-- Select Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} (Current: {{ $product->quantity }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quantity" min="1" placeholder="Enter quantity" required>
                </div>

                <div class="form-group">
                    <label>Reason</label>
                    <select name="reason">
                        <option value="Restock">Restock</option>
                        <option value="Purchase">Purchase</option>
                        <option value="Return">Return</option>
                        <option value="Manual Adjustment">Manual Adjustment</option>
                        <option value="Damaged">Damaged</option>
                        <option value="Lost">Lost</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Note (Optional)</label>
                    <textarea name="note" rows="2" placeholder="Additional notes..."></textarea>
                </div>

                <button type="submit" class="submit-btn">Update Stock</button>
            </form>
        </div>

        <!-- Low Stock Alert -->
        <div class="section-box">
            <h3>⚠️ Stock Status</h3>
            <div style="overflow-x:auto; max-height:380px; overflow-y:auto;">
                <table class="inv-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td><strong>{{ $product->quantity }}</strong></td>
                            <td>
                                @if($product->quantity == 0)
                                    <span class="badge-out">Out of Stock</span>
                                @elseif($product->quantity <= 10)
                                    <span class="badge-low">Low Stock</span>
                                @else
                                    <span class="badge-ok">In Stock</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- History Log — full width -->
        <div class="section-box full-width">
            <h3>📋 Stock History (Last 50)</h3>
            <div style="overflow-x:auto; max-height:350px; overflow-y:auto;">
                <table class="log-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Before</th>
                            <th>After</th>
                            <th>Reason</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                            <td>{{ $log->product->name ?? 'Deleted' }}</td>
                            <td>
                                @if($log->type === 'in')
                                    <span class="badge-in">⬆ IN</span>
                                @else
                                    <span class="badge-out-log">⬇ OUT</span>
                                @endif
                            </td>
                            <td><strong>{{ $log->quantity }}</strong></td>
                            <td>{{ $log->quantity_before }}</td>
                            <td>{{ $log->quantity_after }}</td>
                            <td>{{ $log->reason ?? '-' }}</td>
                            <td>{{ $log->note ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" style="text-align:center; color:#999;">No history yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
function setType(type) {
    document.getElementById('type-input').value = type;

    const btnIn  = document.getElementById('btn-in');
    const btnOut = document.getElementById('btn-out');

    if (type === 'in') {
        btnIn.classList.add('active');
        btnOut.classList.remove('active');
    } else {
        btnOut.classList.add('active');
        btnIn.classList.remove('active');
    }
}
</script>

@endsection