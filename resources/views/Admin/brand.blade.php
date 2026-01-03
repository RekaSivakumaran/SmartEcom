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

        /* ===== COMMON MODAL OVERLAY ===== */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    justify-content: center;
    align-items: center;
    z-index: 999;
}

/* ===== MODAL BOX ===== */
.modal-content {
    background: #fff;
    width: 380px;
    max-width: 95%;
    border-radius: 12px;
    padding: 20px 25px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.25);
    animation: fadeIn 0.25s ease;
}

@keyframes fadeIn {
    from { transform: scale(0.92); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

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
    box-shadow: 0 0 6px rgba(23,162,184,0.35);
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 18px;
}

.save-btn {
    background: #17a2b8;
    color: #fff;
    border: none;
    padding: 8px 18px;
    border-radius: 6px;
    cursor: pointer;
}

.save-btn:hover {
    background: #138496;
}

.cancel-btn {
    background: #dc3545;
    color: #fff;
    border: none;
    padding: 8px 18px;
    border-radius: 6px;
    cursor: pointer;
}

.cancel-btn:hover {
    background: #bd2130;
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

  

    <!-- Buttons above table -->
    <!-- <div class="button-container">
        <button class="action-btn add-btn">Add Customer</button>
        <button class="action-btn" style="background-color:#6c757d;">Export CSV</button>
        <button class="action-btn" style="background-color:#17a2b8;">Refresh Table</button>
    </div> -->

    <!-- Table Container -->
    <div class="button-container">
    <h2 style="font-size: 25px;">Brand Management</h2>
      <!-- Success Message -->
        @if(session('success'))
            <div id="brandSuccessMsg" class="alert alert-success"  style="display:none; margin-left: 270px; padding: 8px 10px; font-size: 14px; color: #28a745; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px;">
                {{ session('success') }}
            </div>
        @endif

    <div class="action-buttons">
        <button class="action-btn add-btn" onclick="openPopup()">Add Brand</button>
        <!-- <button class="action-btn" style="background-color:#17a2b8;" onclick="location.reload()">Refresh Table</button> -->
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Brand Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($brands as $brand)
            <tr>
                <td>{{ $brand->id }}</td>
                <td>{{ $brand->brandname }}</td>
                <td>{{ $brand->status }}</td>
                <td>
                    <button class="action-btn view-btn" onclick="alert('Brand: {{ $brand->brandname }}\nStatus: {{ $brand->status }}')">View</button>

                    <button class="action-btn edit-btn" 
                        onclick="openEditPopup({{ $brand->id }}, '{{ $brand->brandname }}', '{{ $brand->status }}')">
                        Edit
                    </button>

                    <form action="{{ route('brand.destroy', $brand->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this brand?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


    <div id="brandPopup" class="modal">
    <div class="modal-content">

        <h3 id="brandModalTitle">Add Brand</h3>

      
        <form id="brandForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="brand_id">

            <!-- Brand Name -->
            <div class="form-group">
                <label>Brand Name</label>
                <input type="text" name="brandname" id="brandname" value="{{ old('brandname') }}" required>
                @error('brandname')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Status -->
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="brandStatus">
                    <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="modal-footer">
                <button type="button" class="cancel-btn" onclick="closeBrandPopup()">Cancel</button>
                <button type="submit" id="saveBrandBtn" class="save-btn">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
window.addEventListener('DOMContentLoaded', (event) => {
    const successMsg = document.getElementById('brandSuccessMsg');
    @if(session('success'))
        successMsg.innerText = "{{ session('success') }}";
        successMsg.style.display = 'block';

        // Close popup after 2 seconds
        setTimeout(() => {
            successMsg.style.display = 'none';
         }, 2000);

         
    @endif
});


</script>


<script>
    function openPopup() {
    const form = document.getElementById('brandForm');

    document.getElementById('brandModalTitle').innerText = 'Add Brand';
    document.getElementById('saveBrandBtn').innerText = 'Save';
    form.action = "{{ route('brand.store') }}";

    form.reset();
    document.getElementById('brand_id').value = '';

    document.getElementById('brandPopup').style.display = 'flex';
}

function openEditBrandPopup(id, name, status) {
    const form = document.getElementById('brandForm');

    document.getElementById('brandModalTitle').innerText = 'Edit Brand';
    document.getElementById('saveBrandBtn').innerText = 'Update';
    form.action = "/brand/update/" + id;  

    document.getElementById('brand_id').value = id;
    document.getElementById('brandname').value = name;
    document.getElementById('brandStatus').value = status;

    document.getElementById('brandPopup').style.display = 'flex';
}

function closeBrandPopup() {
    document.getElementById('brandPopup').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('brandPopup');
    if(event.target == modal){
        closeBrandPopup();
    }
}

</script>

@endsection

