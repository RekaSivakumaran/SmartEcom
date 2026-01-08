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

.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.55);
    justify-content: center;
    align-items: center;
    z-index: 999;
}

.modal-content {
    background: #ffffff;
    width: 650px;        
    max-width: 95%;
    border-radius: 14px;
    padding: 20px 24px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.35);
    animation: popupFade 0.25s ease;
    max-height: 97vh;    
    overflow-y: auto;
}

@keyframes popupFade {
    from { transform: translateY(-10px) scale(0.96); opacity: 0; }
    to { transform: translateY(0) scale(1); opacity: 1; }
}

/* Two-column layout */
.form-row {
    display: flex;
    gap: 12px;
    margin-bottom: 14px;
}

.form-row .form-group {
    flex: 1;
}

/* Label + Input styling */
.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 5px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 8px 12px;
    font-size: 14px;
    border-radius: 7px;
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    transition: 0.25s;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #3b82f6;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.25);
}

.form-group input:disabled {
    background: #e5e7eb;
    cursor: not-allowed;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 18px;
}

.save-btn {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: #fff;
    border: none;
    padding: 9px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

.cancel-btn {
    background: #ef4444;
    color: #fff;
    border: none;
    padding: 9px 18px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

.text-danger {
    color: red;
    font-size: 13px;
    margin-top: 2px;
    display: block;
}

.textarea-large {
    min-height: 60px;   /* increase height */
    resize: vertical;    /* allow resize */
    width: 100%;  
}

.textarea-large:focus {
    outline: none;
    border-color: #17a2b8;
    box-shadow: 0 0 6px rgba(23,162,184,0.35);
}

.textarea-large {
    overflow: hidden;
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
    <h2 style="font-size: 25px;">Product Management</h2>
     @if(session('success'))
            <div id="brandSuccessMsg" class="alert alert-success"  style="display:none; margin-left: 270px; padding: 8px 10px; font-size: 14px; color: #28a745; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px;">
                {{ session('success') }}
            </div>
        @endif

   <div class="action-buttons">
    <button class="action-btn add-btn" onclick="openAddItemPopup()">Add Product</button>
    
</div>
</div>

    <!-- Buttons above table -->
    <!-- <div class="button-container">
        <button class="action-btn add-btn">Add Customer</button>
        <button class="action-btn" style="background-color:#6c757d;">Export CSV</button>
        <button class="action-btn" style="background-color:#17a2b8;">Refresh Table</button>
    </div> -->

    <!-- Table Container -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                     <th>#</th>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Brand</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Status</th>
            <th>Actions</th>
                </tr>
            </thead>
          <tbody>
@forelse($products as $index => $product)
<tr>
    <td>{{ $index + 1 }}</td>

    <td>
        @if($product->image)
       <img src="{{ asset($product->image) }}" 
     alt="{{ $product->name }}" 
     style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px;">

        @else
            <span>No Image</span>
        @endif
    </td>

    <td>{{ $product->name }}</td>

    <td>{{ $product->mainCategory->Maincategoryname ?? '-' }}</td>


    <td>{{ $product->brand->brandname ?? '-' }}</td>

    <td>Rs. {{ number_format($product->price, 2) }}</td>

    <td>{{ $product->quantity ?? 0 }}</td>

    <td>
        <span class="badge {{ $product->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
            {{ $product->status }}
        </span>
    </td>

    <td>
        
        <button class="action-btn edit-btn" onclick="editItem({{ $product->id }})">Edit</button>
        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="action-btn delete-btn">Delete</button>
</form>

    </td>
</tr>
@empty
<tr>
    <td colspan="9" style="text-align:center;">No products found</td>
</tr>
@endforelse
</tbody>

        </table>
    </div>

<div id="itemPopup" class="modal">
    <div class="modal-content">
        <h3 id="modalTitle">Add Item</h3>


        <form id="itemForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="product_id" name="product_id">
             
            <div class="form-row">
                <input type="hidden" id="product_id" name="product_id">

                <div class="form-group">
                    <label>Item Name</label>
                    <input type="text" name="item_name" value="{{ old('item_name') }}" required>
                    @error('item_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="0.01" id="price" name="price" value="{{ old('price', 0) }}">
                    @error('price')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Row 2: Main Category + Sub Category -->
            <div class="form-row">
                <div class="form-group">
                    <label>Main Category</label>
                    <select name="main_category_id" id="main_category_id">
                        <option value="">Select Main Category</option>
                        @foreach($mainCategories as $main)
                            <option value="{{ $main->id }}" {{ old('main_category_id') == $main->id ? 'selected' : '' }}>
                                {{ $main->Maincategoryname }}
                            </option>
                        @endforeach
                    </select>
                    @error('main_category_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Sub Category</label>
                    <select name="sub_category_id" id="sub_category_id">
                        <option value="">Select Sub Category</option>
                    </select>
                    @error('sub_category_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Row 3: Brand + Quantity -->
            <div class="form-row">
                <div class="form-group">
                    <label>Brand</label>
                    <select name="brand_id">
                        <option value="">Select Brand</option>
                        @foreach($Brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->brandname }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quantity" value="{{ old('quantity', 0) }}">
                    @error('quantity')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Row 4: Discount Type + Rate -->
            <div class="form-row">
                <div class="form-group">
                    <label>Discount Type</label>
                    <select id="discount_type" name="discount_type">
                        <option value="none" {{ old('discount_type') == 'none' ? 'selected' : '' }}>No Discount</option>
                        <option value="rate" {{ old('discount_type') == 'rate' ? 'selected' : '' }}>Discount Rate (%)</option>
                        <option value="amount" {{ old('discount_type') == 'amount' ? 'selected' : '' }}>Discount Amount</option>
                    </select>
                    @error('discount_type')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Discount Rate (%)</label>
                    <input type="number" step="0.01" id="discount_rate" name="discount_rate" value="{{ old('discount_rate', 0) }}" {{ old('discount_type') != 'rate' ? 'disabled' : '' }}>
                    @error('discount_rate')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Row 5: Discount Amount + Status -->
            <div class="form-row">
                <div class="form-group">
                    <label>Discount Amount</label>
                    <input type="number" step="0.01" id="discount_amount" name="discount_amount" value="{{ old('discount_amount', 0) }}" {{ old('discount_type') != 'amount' ? 'disabled' : '' }}>
                    @error('discount_amount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                <label>Item Image</label>
                <input type="file" name="image">
                @error('image')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
                  <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="description" class="form-control textarea-large" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                @enderror
            </div>
            </div>

            <!-- Row 6: Image -->
           

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="cancel-btn" onclick="closeItemPopup()">Cancel</button>
                <button type="submit" class="save-btn">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            document.getElementById('itemPopup').style.display = 'flex';
        @endif
    });
</script>

<script>
window.addEventListener('DOMContentLoaded', (event) => {
    const successMsg = document.getElementById('brandSuccessMsg');
    @if(session('success'))
        successMsg.innerText = "{{ session('success') }}";
        successMsg.style.display = 'block';
       
        setTimeout(() => {
            successMsg.style.display = 'none';
         }, 2000);

         
    @endif
});


</script>
<script>
    const subcategories = @json($subcategories);
    const products = @json($products);
</script>



<script>

const price = document.getElementById('price');
const discountType = document.getElementById('discount_type');
const discountRate = document.getElementById('discount_rate');
const discountAmount = document.getElementById('discount_amount');

// Recalculate discount based on type
function recalcDiscount() {
    let p = parseFloat(price.value) || 0;

    if (discountType.value === 'rate') {
        let r = parseFloat(discountRate.value) || 0;
        discountAmount.value = ((p * r) / 100).toFixed(2);
    } else if (discountType.value === 'amount') {
        let a = parseFloat(discountAmount.value) || 0;
        discountRate.value = p > 0 ? ((a / p) * 100).toFixed(2) : 0;
    }
}

// Reset discount on type change
discountType.addEventListener('change', () => {
    discountRate.value = 0;
    discountAmount.value = 0;

    discountRate.disabled = true;
    discountAmount.disabled = true;

    if (discountType.value === 'rate') discountRate.disabled = false;
    if (discountType.value === 'amount') discountAmount.disabled = false;
});

// Recalc when rate, amount, or price changes
discountRate.addEventListener('input', () => { if(discountType.value==='rate') recalcDiscount(); });
discountAmount.addEventListener('input', () => { if(discountType.value==='amount') recalcDiscount(); });
price.addEventListener('input', recalcDiscount);

document.getElementById('main_category_id').addEventListener('change', function () {
    const mainId = this.value;
    const subSelect = document.getElementById('sub_category_id');

    // Always reset subcategory
    subSelect.innerHTML = '<option value="">Select Sub Category</option>';

    if (!mainId) return;

    subcategories.forEach(sc => {
        if (sc.main_category_id == mainId) {
            const option = document.createElement('option');
            option.value = sc.id;
            option.textContent = sc.sub_category_name;
            subSelect.appendChild(option);
        }
    });
});



function openAddItemPopup() {
    const productStoreRoute = "{{ route('products.store') }}";
    document.getElementById('modalTitle').textContent = 'Add Item';

    const form = document.getElementById('itemForm'); 
    form.action = productStoreRoute;
    form.reset();

    const hiddenId = document.getElementById('product_id');
    if (hiddenId) hiddenId.value = '';
    document.getElementById('sub_category_id').innerHTML =
        '<option value="">Select Sub Category</option>';

    document.getElementById('discount_rate').disabled = true;
    document.getElementById('discount_amount').disabled = true;

    document.getElementById('itemPopup').style.display = 'flex';  
}

 function editItem(productId){
    const product = products.find(p => p.id == productId);
    if(!product) return;

    const form = document.getElementById('itemForm');
    form.reset();

    document.getElementById('modalTitle').textContent = 'Edit Item';
    form.action = `/products/${product.id}`;

    // Ensure _method input exists
    let methodInput = form.querySelector("input[name='_method']");
    if(!methodInput){
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        form.appendChild(methodInput);
    }
    methodInput.value = 'PUT';

    // Use querySelector to access each input safely
    form.querySelector('input[name="product_id"]').value = product.id;
    form.querySelector('input[name="item_name"]').value = product.name; // <--- use product.name not product.item_name
    form.querySelector('input[name="price"]').value = product.price;
    form.querySelector('input[name="quantity"]').value = product.quantity ?? 0;
    form.querySelector('select[name="status"]').value = product.status;
    form.querySelector('select[name="brand_id"]').value = product.brand_id ?? '';

    document.getElementById('main_category_id').value = product.main_category_id;
    document.getElementById('description').value = product.description;

    const subSelect = document.getElementById('sub_category_id');
    subSelect.innerHTML = '<option value="">Select Sub Category</option>';
    subcategories
        .filter(sc => sc.main_category_id == product.main_category_id)
        .forEach(sc => {
            const opt = document.createElement('option');
            opt.value = sc.id;
            opt.textContent = sc.sub_category_name;
            subSelect.appendChild(opt);
        });
    subSelect.value = product.sub_category_id;

    const discountTypeEl = document.getElementById('discount_type');
    const discountRateEl = document.getElementById('discount_rate');
    const discountAmountEl = document.getElementById('discount_amount');

    discountTypeEl.value = product.discount_type;
    discountRateEl.disabled = true;
    discountAmountEl.disabled = true;

    if(product.discount_type === 'rate'){
        discountRateEl.disabled = false;
        discountRateEl.value = product.discount_rate;
        discountAmountEl.value = product.discount_amount;
    }
    if(product.discount_type === 'amount'){
        discountAmountEl.disabled = false;
        discountAmountEl.value = product.discount_amount;
        discountRateEl.value = product.discount_rate;
    }

    document.getElementById('itemPopup').style.display = 'flex';
}

function closeItemPopup() {
    const form = document.getElementById('itemForm');
    form.reset();
    form.querySelectorAll('.text-danger').forEach(span => span.textContent = '');
    document.getElementById('discount_rate').disabled = true;
    document.getElementById('discount_amount').disabled = true;
    document.getElementById('itemPopup').style.display = 'none';
}

</script>


@endsection

