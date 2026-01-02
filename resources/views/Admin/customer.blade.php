@extends('Layout.app')

@section('content')

<style>
/* Header */
h2 {
    font-size: 25px;
    color: #333;
}

/* Header buttons */
.button-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 15px;
    padding: 10px;
}

.action-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    color: #fff;
    font-size: 14px;
    cursor: pointer;
    margin-left: 5px;
}

/* Table container */
.table-container {
    width: 98%;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    padding: 20px;
    margin: 10px;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px 15px;
    text-align: left;
}

th {
    background-color: #17a2b8;
    color: #fff;
    font-size: 14px;
    text-transform: uppercase;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #e0f0ff;
}

/* View button */
.view-btn {
    background-color: #138496;
    padding: 6px 12px;
    border-radius: 4px;
    border: none;
    color: #fff;
    cursor: pointer;
    font-size: 13px;
}

/* ===== STATUS BADGE ===== */
.status-badge {
    padding: 5px 10px;
    border-radius: 5px;
    color: white;
    font-weight: bold;
    display: inline-block;
}

.status-badge.active {
    background-color: green; /* Active = green */
}

.status-badge.block {
    background-color: red;   /* Block = red */
}

@media(max-width: 768px) {
    .button-container {
        flex-direction: column;
        align-items: flex-end;
    }
}


.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.4);
    z-index: 999;
    justify-content: center;
    align-items: center;
}

/* ===== MODAL BOX ===== */
.modal-content {
    background: #fff;
    width: 380px;
    max-width: 90%;
    border-radius: 12px;
    padding: 20px 25px;
    animation: fadeIn 0.3s ease;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

@keyframes fadeIn {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

/* ===== HEADER ===== */
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.modal-header h3 {
    font-size: 18px;
    color: #333;
}

.close-btn {
    font-size: 22px;
    cursor: pointer;
    color: #aaa;
}

.close-btn:hover {
    color: #000;
}

/* ===== FORM ===== */
.form-group {
    margin-bottom: 14px;
}

.form-group label {
    display: block;
    font-size: 13px;
    margin-bottom: 4px;
    font-weight: 600;
    color: #333;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 9px 12px;
    font-size: 14px;
    border-radius: 6px;
    border: 1px solid #ccc;
    transition: 0.3s;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #17a2b8;
    box-shadow: 0 0 6px rgba(23,162,184,0.4);
}

/* ===== FOOTER ===== */
.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 15px;
}

.save-btn {
    background: #17a2b8;
    color: #fff;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.save-btn:hover {
    background: #138496;
}

.cancel-btn {
    background: #dc3545;
    color: #fff;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.cancel-btn:hover {
    background: #bd2130;
}

/* ===== RESPONSIVE ===== */
@media(max-width: 420px){
    .modal-content {
        width: 95%;
        padding: 15px 20px;
    }

    .form-group input,
    .form-group select {
        font-size: 13px;
        padding: 8px 10px;
    }

    .save-btn, .cancel-btn {
        padding: 6px 12px;
        font-size: 13px;
    }
}
</style>

<!-- Header -->
<div class="button-container">
    <h2>Customer Management</h2>
    <div>
        <!-- <button class="action-btn" style="background:#6c757d;">Export CSV</button> -->
        <button class="action-btn" style="background:#17a2b8;" onclick="refreshPage()">Refresh Table</button>
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
       <tbody>
@foreach($customers as $customer)
<tr data-id="{{ $customer->id }}">
    <td class="customer-id">{{ $customer->id }}</td>
    <td class="customer-name">{{ $customer->name }}</td>
    <td class="customer-email">{{ $customer->email }}</td>
    <td class="customer-phone">{{ $customer->mobile }}</td>
    <td>
        <span class="status-badge {{ trim(strtolower($customer->status)) == 'active' ? 'active' : 'block' }}">
            {{ ucfirst(trim($customer->status)) }}
        </span>
    </td>
    <td>
        <button class="view-btn" onclick="openModal({{ $customer->id }})">View</button>
    </td>
</tr>
@endforeach
</tbody>

    </table>
</div>



<div id="customerModal" class="modal">
    <div class="modal-content">
        <!-- Header -->
        <div class="modal-header">
            <h3>View Customer</h3>
            <span class="close-btn" onclick="closeModal()">&times;</span>
        </div>

        <!-- Form -->
        <form id="customerForm" method="POST">
                 @csrf
             <input type="hidden" name="id" id="customer_id">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name"  name="name" placeholder="Enter name" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter email" required>
            </div>

            <div class="form-group">
                <label for="mobile">Mobile Number</label>
                <input type="tel" id="mobile" name="mobile" placeholder="Enter mobile number" required>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="active">Active</option>
                    <option value="block">Block</option>
                </select>
            </div>

            <!-- Footer buttons -->
            <div class="modal-footer">
                <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="save-btn">Save</button>
            </div>
        </form>
    </div>
</div>


<script>
 
 function refreshPage() {
    location.reload();
}

function openModal(customerId) {
    const row = document.querySelector(`tr[data-id='${customerId}']`);
    if (!row) return;
     
    document.getElementById('name').value = row.querySelector('.customer-name').innerText;
    document.getElementById('email').value = row.querySelector('.customer-email').innerText;
    document.getElementById('mobile').value = row.querySelector('.customer-phone').innerText;

    const statusText = row.querySelector('.status-badge').innerText.toLowerCase();
    document.getElementById('status').value = statusText;

    document.getElementById('name').readOnly = true;
    document.getElementById('email').readOnly = true;
    document.getElementById('mobile').readOnly = true;
     
    document.getElementById('customerForm').action = '/customers/update-status/' + customerId;
    document.getElementById('customerModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('customerModal').style.display = 'none';
}


window.onclick = function(event) {
    const modal = document.getElementById('customerModal');
    if (event.target == modal) {
        closeModal();
    }
}

 
</script>

@endsection
