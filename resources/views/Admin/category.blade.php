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

   <div class="action-buttons">
    <button class="action-btn add-btn" onclick="openPopup()">Add Category</button>
    <!-- <button class="action-btn" style="background-color:#6c757d;">Export CSV</button> -->
    <button class="action-btn" style="background-color:#17a2b8;">Refresh Table</button>
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
                <th>Category Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                     <td>1</td>
                <td>Electronics</td>
                <td>Gadgets, devices, and accessories</td>
                <td>Active</td>
                    <td>
                        <button class="action-btn view-btn">View</button>
                        <button class="action-btn edit-btn">Edit</button>
                        <button class="action-btn delete-btn">Delete</button>
                    </td>
                </tr>
                <tr>
                     <td>2</td>
                <td>Clothing</td>
                <td>Apparel and fashion items</td>
                <td>Active</td>
                    <td>
                        <button class="action-btn view-btn">View</button>
                        <button class="action-btn edit-btn">Edit</button>
                        <button class="action-btn delete-btn">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                <td>Books</td>
                <td>Educational books and novels</td>
                <td>Inactive</td>
                    <td>
                        <button class="action-btn view-btn">View</button>
                        <button class="action-btn edit-btn">Edit</button>
                        <button class="action-btn delete-btn">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

<div id="popupModel" class="modal" style="display:none;">
    <div class="modal-content">

        <!-- Header -->
        <div class="modal-header">
            <h3>Main Category</h3>
            <span class="close-btn" onclick="closePopup()">&times;</span>
        </div>

        <!-- Form -->
        <form id="categoryForm" onsubmit="saveCategory(event)">
            <div class="form-group">
                <label>Main Category</label>
                <input type="text" required>
            </div>

           <div class="form-group">
    <label>Description</label>
    <textarea class="form-control textarea-large" rows="4" required></textarea>
</div>



            <div class="form-group">
                <label>Status</label>
                <select>
                    <option value="active">Active</option>
                    <option value="block">Block</option>
                </select>
            </div>

            <div class="form-group">
                <label>Main Category Image</label>
                <input type="file" onchange="previewImage(event)">
                <img id="preview" style="display:none;margin-top:8px;width:120px;">
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="cancel-btn" onclick="closePopup()">Cancel</button>
                <button type="submit" class="save-btn">Save</button>
            </div>
        </form>

    </div>
</div>



<script>
function openPopup() {
    document.getElementById("popupModel").style.display = "flex";
}

function closePopup() {
    document.getElementById("popupModel").style.display = "none";
}

window.onclick = function(event) {
    const modal = document.getElementById('popupModel');
    if (event.target == modal) {
        closeModal();
    }
}
 
</script>


@endsection

