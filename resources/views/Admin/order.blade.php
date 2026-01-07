@extends('Layout.app')

@section('content')

<style>
    /* General Reset */
    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    /* Button container above table */
    .button-container {
        width: 100%;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 15px auto;
        padding: 10px;
        font-size: 26px;
        color: #333;
    }

    .button-container .action-btn {
        padding: 8px 16px;
        margin-left: 2px;
        font-size: 14px;
        font-weight: bold;
    }

    /* Table Container */
    .table-container {
        width: 98%;
        overflow-x: auto;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
        margin: 10px;
    }

    table {
        width: 98%;
        border-collapse: collapse;
        min-width: 500px;
    }

    th, td {
        padding: 10px 12.5px;
        text-align: left;
    }

    th {
        background-color: #17a2b8;
        color: #fff;
        text-transform: uppercase;
        font-size: 14px;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #e0f0ff;
    }

    /* Action Buttons */
    .action-btn {
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        color: #fff;
        font-size: 13px;
        transition: 0.3s;
    }

    .action-btn:hover {
        opacity: 0.85;
    }

    .add-btn {
        background-color: #007bff;
    }

    .edit-btn {
        background-color: #28a745;
    }

    .delete-btn {
        background-color: #dc3545;
    }

    .view-btn {
        background-color: #138496;
    }

    .text-danger {
        color: red;
        font-size: 13px;
        margin-top: 2px;
        display: block;
    }

    /* VIEW ITEMS MODAL */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.4);
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .modal-content {
        background: #fff;
        padding: 20px;
        width: 600px;
        max-height: 90%;
        overflow-y: auto;
        border-radius: 10px;
        box-shadow: 0px 5px 15px rgba(0,0,0,0.3);
        text-align: center;
    }

    #viewOrderItemsTable {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    #viewOrderItemsTable th, #viewOrderItemsTable td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    #viewOrderItemsTable th {
        background-color: #827e7eff;
        font-weight: 600;
    }

    #viewOrderItemsTable img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 5px;
    }

    .modal-footer {
        margin-top: 15px;
        text-align: right;
    }

    .close-btn {
        background-color: #bc1919ff;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    .close-btn:hover {
        background-color: #a01515;
    }

    /* EDIT ORDER MODAL */
    .modalOrder {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .modalOrder-content {
        background: #fff;
        padding: 25px;
        width: 80%;
        max-width: 900px;
        max-height: 90%;
        overflow-y: auto;
        border-radius: 8px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
    }

    .modalOrder-content h3 {
        margin-top: 0;
        margin-bottom: 20px;
        color: #333;
        border-bottom: 2px solid #17a2b8;
        padding-bottom: 10px;
    }

    .modalOrder-content h4 {
        margin-top: 20px;
        margin-bottom: 10px;
        color: #555;
        font-size: 16px;
    }

    .modalOrder-content label {
        display: block;
        margin-top: 12px;
        margin-bottom: 5px;
        font-weight: 600;
        color: #555;
        font-size: 14px;
    }

    .modalOrder-content input[type="text"],
    .modalOrder-content select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        box-sizing: border-box;
        background-color: #f9f9f9;
    }

    .modalOrder-content input[readonly] {
        background-color: #e9ecef;
        cursor: not-allowed;
    }

    .modalOrder-content select {
        background-color: #fff;
        cursor: pointer;
    }

    #editOrderItemsTable {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        margin-bottom: 20px;
    }

    #editOrderItemsTable th,
    #editOrderItemsTable td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }

    #editOrderItemsTable th {
        background-color: #17a2b8;
        color: white;
        font-weight: 600;
    }

    #editOrderItemsTable img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }

    .modalOrder-footer {
        text-align: right;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #ddd;
    }

    .modalOrder-footer button {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        margin-left: 10px;
        transition: 0.3s;
    }

    .modalOrder-footer button[type="button"] {
        background-color: #6c757d;
        color: white;
    }

    .modalOrder-footer button[type="button"]:hover {
        background-color: #5a6268;
    }

    .modalOrder-footer button[type="submit"] {
        background-color: #28a745;
        color: white;
    }

    .modalOrder-footer button[type="submit"]:hover {
        background-color: #218838;
    }

    /* Responsive */
    @media(max-width: 768px) {
        th, td {
            padding: 10px;
        }

        .action-btn {
            padding: 4px 8px;
            font-size: 12px;
        }

        .button-container {
            flex-direction: column;
            align-items: flex-end;
        }

        .button-container .action-btn {
            margin: 5px 0 0 0;
        }

        .modalOrder-content {
            width: 95%;
            padding: 15px;
        }
    }
</style>

<div class="button-container">
    <h2 style="font-size: 25px;">Order Management</h2>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert alert-success" style="width: 98%; margin: 10px; padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" style="width: 98%; margin: 10px; padding: 15px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger" style="width: 98%; margin: 10px; padding: 15px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Table Container -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Bill No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile No</th>
                <th>Billing Address</th>
                <th>Shipping Address</th>
                <th>Status</th>
                <th>Payment Status</th>
                <th>Total</th>
                <th>Items</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ $order->bill_no }}</td>
                <td>{{ $order->name }}</td>
                <td>{{ $order->email }}</td>
                <td>{{ $order->mobile }}</td>
                <td>
                    {{ $order->billing_address1 }}<br>
                    {{ $order->billing_address2 }} 
                    {{ $order->billing_city }}<br>
                    {{ $order->billing_country }}<br>
                    {{ $order->billing_postcode }}
                </td>
                <td>
                    {{ $order->shipping_address1 }}<br>
                    {{ $order->shipping_address2 }} 
                    {{ $order->shipping_city }}<br>
                    {{ $order->shipping_country }}<br>
                    {{ $order->shipping_postcode }}
                </td>
                <td>{{ $order->status }}</td>
                <td>{{ $order->payment_status }}</td>
                <td>Rs. {{ number_format($order->total, 2) }}</td>
                <td>
                    <button class="action-btn view-btn" onclick="openOrderItems({{ $order->id }})">View Items</button>
                </td>
                <td>
                    <button class="action-btn edit-btn" onclick="openEditOrder({{ $order->id }})">Edit</button>                 
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align:center;">No orders found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- VIEW ITEMS POPUP -->
<div id="orderItemsPopup" class="modal">
    <div class="modal-content">
        <h3>View Products</h3>
        <table id="viewOrderItemsTable">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <div class="modal-footer">
            <button class="close-btn" onclick="closeOrderItems()">CLOSE</button>
        </div>
    </div>
</div>

<!-- EDIT ORDER POPUP -->
<div id="editOrderPopup" class="modalOrder">
    <div class="modalOrder-content">
        <h3>Edit Order</h3>

        <form id="editOrderForm" method="POST">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <label>Bill No</label>
            <input type="text" id="bill_no" readonly>

            <label>Name</label>
            <input type="text" id="name" readonly>

            <label>Email</label>
            <input type="text" id="email" readonly>

            <label>Mobile</label>
            <input type="text" id="mobile" readonly>

            <h4>Billing Address</h4>
            <input type="text" id="billing_address1" readonly>
            <input type="text" id="billing_address2" readonly>
            <input type="text" id="billing_city" readonly>
            <input type="text" id="billing_country" readonly>
            <input type="text" id="billing_postcode" readonly>

            <h4>Shipping Address</h4>
            <input type="text" id="shipping_address1" readonly>
            <input type="text" id="shipping_address2" readonly>
            <input type="text" id="shipping_city" readonly>
            <input type="text" id="shipping_country" readonly>
            <input type="text" id="shipping_postcode" readonly>

            <h4>Order Items</h4>
            <table id="editOrderItemsTable">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <label>Order Status</label>
            <select name="status" id="status" required>
                <option value="Pending">Pending</option>
                <option value="Processing">Processing</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
            </select>

            <label>Payment Status</label>
            <select name="payment_status" id="payment_status" required>
                <option value="Pending">Pending</option>
                <option value="Paid">Paid</option>
                <option value="Failed">Failed</option>
            </select>

            <div class="modalOrder-footer">
                <button type="button" onclick="closeEditOrder()">Cancel</button>
                <button type="submit">Update Order</button>
            </div>
        </form>
    </div>
</div>

<script>
    const orders = @json($orders);

    // View Items Popup
    function openOrderItems(orderId) {
        const order = orders.find(o => o.id === orderId);
        if (!order) return;

        const tbody = document.querySelector('#viewOrderItemsTable tbody');
        tbody.innerHTML = '';

        order.items.forEach(item => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    ${item.image_path 
                        ? `<img src="/image/Products/${item.image_path}" alt="Product">`
                        : 'No Image'}
                </td>
                <td>${item.product ? item.product.name : 'Deleted Product'}</td>
                <td>${item.quantity}</td>
                <td>Rs. ${parseFloat(item.price).toFixed(2)}</td>
                <td>Rs. ${parseFloat(item.total).toFixed(2)}</td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('orderItemsPopup').style.display = 'flex';
    }

    function closeOrderItems() {
        document.getElementById('orderItemsPopup').style.display = 'none';
    }

    // Edit Order Popup
    function openEditOrder(orderId) {
        const order = orders.find(o => o.id == orderId);
        if (!order) {
            alert('Order not found');
            return;
        }

        const form = document.getElementById('editOrderForm');
        form.action = `/orders/${order.id}`;

        // Fill form fields
        document.getElementById('bill_no').value = order.bill_no || '';
        document.getElementById('name').value = order.name || '';
        document.getElementById('email').value = order.email || '';
        document.getElementById('mobile').value = order.mobile || '';

        document.getElementById('billing_address1').value = order.billing_address1 || '';
        document.getElementById('billing_address2').value = order.billing_address2 || '';
        document.getElementById('billing_city').value = order.billing_city || '';
        document.getElementById('billing_country').value = order.billing_country || '';
        document.getElementById('billing_postcode').value = order.billing_postcode || '';

        document.getElementById('shipping_address1').value = order.shipping_address1 || '';
        document.getElementById('shipping_address2').value = order.shipping_address2 || '';
        document.getElementById('shipping_city').value = order.shipping_city || '';
        document.getElementById('shipping_country').value = order.shipping_country || '';
        document.getElementById('shipping_postcode').value = order.shipping_postcode || '';

        document.getElementById('status').value = order.status || 'Pending';
        document.getElementById('payment_status').value = order.payment_status || 'Pending';

        // Fill order items table
        const tbody = document.querySelector('#editOrderItemsTable tbody');
        tbody.innerHTML = '';

        if (order.items && order.items.length > 0) {
            order.items.forEach(item => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${item.product?.name ?? 'Deleted Product'}</td>
                    <td>
                        ${item.image_path 
                            ? `<img src="/image/Products/${item.image_path}" alt="Product">`
                            : 'No Image'}
                    </td>
                    <td>${item.quantity}</td>
                    <td>Rs. ${parseFloat(item.price).toFixed(2)}</td>
                    <td>Rs. ${parseFloat(item.total).toFixed(2)}</td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">No items found</td></tr>';
        }

        document.getElementById('editOrderPopup').style.display = 'flex';
    }

    function closeEditOrder() {
        document.getElementById('editOrderPopup').style.display = 'none';
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const viewModal = document.getElementById('orderItemsPopup');
        const editModal = document.getElementById('editOrderPopup');
        
        if (event.target == viewModal) {
            closeOrderItems();
        }
        if (event.target == editModal) {
            closeEditOrder();
        }
    }
</script>

@endsection