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

/* ===== HEADER ===== */
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 1px solid #eaeaea;
}

.modal-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

/* ===== CLOSE BUTTON ===== */
.close-btn {
    font-size: 22px;
    cursor: pointer;
    color: #aaa;
    transition: 0.2s;
}

.close-btn:hover {
    color: #000;
}

/* ===== FORM GROUP ===== */
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

/* ===== INPUT & SELECT ===== */
.form-group input,
.form-group select {
    width: 100%;
    padding: 9px 12px;
    font-size: 14px;
    border-radius: 6px;
    border: 1px solid #ccc;
    transition: 0.3s;
    background: #fff;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #17a2b8;
    box-shadow: 0 0 6px rgba(23,162,184,0.35);
}

/* ===== IMAGE UPLOAD PREVIEW ===== */
.form-group input[type="file"] {
    padding: 6px;
}

#preview {
    display: none;
    margin-top: 8px;
    width: 120px;
    border-radius: 6px;
    border: 1px solid #ddd;
}

/* ===== FOOTER ===== */
.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 18px;
}

/* ===== BUTTONS ===== */
.save-btn {
    background: #17a2b8;
    color: #fff;
    border: none;
    padding: 8px 18px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: 0.2s;
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
    font-size: 14px;
    transition: 0.2s;
}

.cancel-btn:hover {
    background: #bd2130;
}


.form-control {
    width: 100%;
    padding: 10px 12px;
    font-size: 14px;
    border-radius: 6px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

.textarea-large {
    min-height: 120px;   /* increase height */
    resize: vertical;    /* allow resize */
}

.textarea-large:focus {
    outline: none;
    border-color: #17a2b8;
    box-shadow: 0 0 6px rgba(23,162,184,0.35);
}

.textarea-large {
    overflow: hidden;
}

.text-danger {
    color: red;
    font-size: 13px;
    margin-top: 2px;
    display: block;
}
.desc {
    max-width: 200px;        /* control width */
    white-space: normal;     /* allow next line */
    word-wrap: break-word;   /* break long words */
    line-height: 1.5;
}



/* ===== RESPONSIVE ===== */
@media (max-width: 420px) {
    .modal-content {
        width: 95%;
        padding: 15px 20px;
    }

    .save-btn,
    .cancel-btn {
        padding: 6px 14px;
        font-size: 13px;
    }
}

      
    </style>

 <!-- <h1>Customer Management</h1> -->

 <div class="button-container">
    <h2 style="font-size: 25px;">Category Management</h2>
  @if(session('success'))
            <div id="brandSuccessMsg" class="alert alert-success"  style="display:none; margin-left: 270px; padding: 8px 10px; font-size: 14px; color: #28a745; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px;">
                {{ session('success') }}
            </div>
        @endif
   <div class="action-buttons">
    <button class="action-btn add-btn" onclick="openPopup()">Add Category</button>
    <!-- <button class="action-btn" style="background-color:#6c757d;">Export CSV</button> -->
    <!-- <button class="action-btn" style="background-color:#17a2b8;">Refresh Table</button> -->
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
            <th>ID</th>
            <th>Image</th>
            <th>Sub Category Name</th>
            <th>Description</th>
            <th>Main Category Name</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($subcategories as $subcategory)
<tr>
    <td>{{ $subcategory->id }}</td>
    <td>
    @if($subcategory->image && file_exists(public_path($subcategory->image)))
        <img src="{{ asset($subcategory->image) }}" 
             alt="{{ $subcategory->sub_category_name }}" 
             style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px;">
    @else
        <span>No Image</span>
    @endif
</td>
    </td>
    <td>{{ $subcategory->sub_category_name }}</td>
        <td class="desc">{{ $subcategory->description }}</td>

    <td>{{ $subcategory->mainCategory->Maincategoryname ?? 'N/A' }}</td>
    <td>{{ $subcategory->status }}</td>
      <td>
        <button class="action-btn edit-btn" 
        onclick="openEditPopup(
            {{ $subcategory->id }}, 
            '{{ $subcategory->sub_category_name }}', 
            '{{ $subcategory->description }}', 
            {{ $subcategory->main_category_id }}, 
            '{{ $subcategory->status }}', 
            '{{ $subcategory->image }}'
        )">
        Edit
        </button>


                <form method="POST" action="{{ route('sub-category.destroy', $subcategory->id) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this sub-category?')">
                    Delete
                    </button>
                </form>

            </td>
</tr>
@endforeach

    </tbody>
</table>




 
    </div>

<div id="popupModel" class="modal">
    <div class="modal-content">

        <h3 id="modalTitle">Add Sub Category</h3>

        <form id="categoryForm"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="category_id">

            <!-- Sub Category Name -->
            <div class="form-group">
                <label>Sub Category Name</label>
                <input type="text"
                       name="sub_category_name"
                       id="sub_category_name"
                       value="{{ old('sub_category_name') }}"
                       required>
                @error('sub_category_name')
                    <span class="text-danger error-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description -->
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"
                          id="description"
                          rows="4"
                          required>{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-danger error-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Main Category -->
            <div class="form-group">
                <label>Main Category</label>
                <select name="main_category_id" id="main_category_id" required>
                    <option value="">-- Select Main Category --</option>
                    @foreach($mainCategories as $main)
                        <option value="{{ $main->id }}"
                            {{ old('main_category_id') == $main->id ? 'selected' : '' }}>
                            {{ $main->Maincategoryname }}
                        </option>
                    @endforeach
                </select>
                @error('main_category_id')
                    <span class="text-danger error-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Status -->
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="status">
                    <option value="Active" {{ old('status')=='Active'?'selected':'' }}>Active</option>
                    <option value="Inactive" {{ old('status')=='Inactive'?'selected':'' }}>Inactive</option>
                </select>
                @error('status')
                    <span class="text-danger error-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Image -->
            <div class="form-group">
                <label>Sub Category Image</label>
                <input type="file"
                       name="image"
                       accept="image/*"
                       onchange="previewImage(event)">
                <img id="preview"
                     style="display:none;margin-top:8px;width:120px;">
                @error('image')
                    <span class="text-danger error-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="modal-footer">
                <button type="button" class="cancel-btn"
                        onclick="closePopup()">Cancel</button>
                <button type="submit" id="saveBtn"
                        class="save-btn">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Auto open popup on validation error -->
@if($errors->any())
<script>
    document.getElementById('popupModel').style.display = 'flex';
</script>
@endif

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

function openPopup() {
    const form = document.getElementById('categoryForm');

    document.getElementById('modalTitle').innerText = 'Add Sub Category';
    document.getElementById('saveBtn').innerText = 'Save';

    form.action = "{{ route('subcategories.store') }}"; 
    form.reset();

    const hiddenId = document.getElementById('category_id');
    if (hiddenId) hiddenId.value = '';

    const preview = document.getElementById('preview');
    if (preview) preview.style.display = 'none';

    document.getElementById('popupModel').style.display = 'flex'; // ✅ fixed
}

 

function openEditPopup(id, name, description, mainId, status, image) {
    const form = document.getElementById('categoryForm');

    document.getElementById('modalTitle').innerText = 'Edit Sub Category';
    document.getElementById('saveBtn').innerText = 'Update';

    form.action = "/subcategories/update/" + id;

    document.getElementById('category_id').value = id;
    document.getElementById('sub_category_name').value = name;
    document.getElementById('description').value = description;
    document.getElementById('main_category_id').value = mainId;
    document.getElementById('status').value = status;

    const img = document.getElementById('preview');
    if (image) {
        img.src = image;
        img.style.display = 'block';
    }

    document.getElementById('popupModel').style.display = 'flex';
}

function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;

    const img = document.getElementById('preview');
    img.src = URL.createObjectURL(file);
    img.style.display = 'block';
}

function closePopup() {
    document.getElementById('popupModel').style.display = 'none';
}

// window.onclick = function (event) {
//     const modal = document.getElementById('popupModel');
//     if (event.target === modal) {
//         closePopup();
//     }
// };
</script>


@endsection

