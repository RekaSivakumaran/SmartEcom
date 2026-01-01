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

      
    </style>

 <!-- <h1>Customer Management</h1> -->

 <div class="button-container">
    <h2 style="font-size: 25px;">Brand Management</h2>

   <div class="action-buttons">
    <button class="action-btn add-btn">Add Brand</button>
    <button class="action-btn" style="background-color:#6c757d;">Export CSV</button>
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
                <th>Brand Name</th>                
                <th>Status</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                     <td>1</td>
                <td>Signature</td>                
                <td>Active</td>
                    <td>
                        <button class="action-btn view-btn">View</button>
                        <button class="action-btn edit-btn">Edit</button>
                        <button class="action-btn delete-btn">Delete</button>
                    </td>
                </tr>
                <tr>
                     <td>2</td>
                <td>Quartz</td>
                <td>Active</td>
                    <td>
                        <button class="action-btn view-btn">View</button>
                        <button class="action-btn edit-btn">Edit</button>
                        <button class="action-btn delete-btn">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                <td>Moose</td>
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
@endsection

