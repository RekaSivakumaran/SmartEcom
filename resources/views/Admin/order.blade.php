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
    /* margin: 0; */
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
            width: 100%;
            border-collapse: collapse;
            min-width: 500px;
        }

        th, td {
            padding: 12px 15px;
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
            /* margin-right: 5px; */
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
        }

        .action-buttons {
    
      /* space below buttons */
}

      
    </style>

 <!-- <h1>Customer Management</h1> -->

 <div class="button-container">
    <h2 style="font-size: 25px;">Order Management</h2>
     <!-- @if(session('success'))
            <div id="brandSuccessMsg" class="alert alert-success"  style="display:none; margin-left: 270px; padding: 8px 10px; font-size: 14px; color: #28a745; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px;">
                {{ session('success') }}
            </div>
        @endif -->

   <!-- <div class="action-buttons">
    <button class="action-btn add-btn" onclick="openAddItemPopup()">Add Product</button>
    
</div> -->
</div>

     

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
                        <button class="action-btn view-btn" onclick="openOrderItems({{ $order->id }})">View</button>

                <!-- @foreach($order->items as $item)
                    {{ $item->product->name ?? 'Product deleted' }} (x{{ $item->quantity }}) <br>
                @endforeach -->
            </td>
            <td>
                    <button class="action-btn edit-btn" onclick="openOrderItems({{ $order->id }})">View Items</button>
                 
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


    <div id="orderItemsPopup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background: rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:9999;">
    
    <div style="background:#fff; padding:20px; width:80%; max-height:80%; overflow-y:auto; margin:auto; border-radius:5px;">
        <h3>Order Items</h3>
        <table border="1" cellpadding="5" cellspacing="0" id="orderItemsTable" style="width:100%;">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Image</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <div style="text-align:right; margin-top:10px;">
            <button onclick="closeOrderItems()">Close</button>
        </div>
    </div>
</div>

<script>
    const orders = @json($orders);

    function openOrderItems(orderId){
        const order = orders.find(o => o.id === orderId);
        if(!order) return;

        const tbody = document.querySelector('#orderItemsTable tbody');
        tbody.innerHTML = '';

        order.items.forEach(item => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.product ? item.product.name : 'Deleted Product'}</td>
                <td>${item.image_path ? `<img src="/${item.image_path}" width="50" />` : 'No Image'}</td>
                <td>${item.quantity}</td>
                <td>Rs.${parseFloat(item.price).toFixed(2)}</td>
                <td>Rs.${parseFloat(item.total).toFixed(2)}</td>
            `;
            tbody.appendChild(tr);
        });

        // Show popup
        const popup = document.getElementById('orderItemsPopup');
        popup.style.display = 'flex';
        popup.style.justifyContent = 'center';
        popup.style.alignItems = 'center';
    }

    function closeOrderItems(){
        document.getElementById('orderItemsPopup').style.display = 'none';
    }
</script>


 
@endsection

