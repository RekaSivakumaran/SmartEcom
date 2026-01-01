@extends('Layout.app')


@section('content')

<style>

 .subheader {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 15px;
    /* margin: 0; */
    padding: 10px;
     font-size: 26px;
    color: #333;
}

.add-btn {
    background: #0674e7;
    color: #fff;
    padding: 10px 18px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
     padding: 8px 16px;
             
            font-weight: bold;
}

.add-btn:hover {
    background: #005bb5;
}

.user-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    margin: 15px;
}

.user-card {
    background: #fff;
    border-radius: 14px;
    padding: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: 0.2s;
}

.user-card:hover {
    transform: translateY(-4px);
}

/* Card Top row */
.card-top {
    display: flex;
    align-items: center;
    gap: 15px;
}

.avatarUser {
    width: 65px;
    height: 65px;
    border-radius: 50%;
    border: 3px solid #ddd;
}

.info h3 {
    margin: 0;
    font-size: 18px;
    margin-bottom: 4px;
}

.info p {
    margin: 0;
    color: #777;
    font-size: 14px;
}

/* Status badge */
.status {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 600;
    color: #fff;
    margin-top: 8px;
}

.active { background: #27ae60; }
.inactive { background: #c0392b; }

/* Footer - buttons */
.card-footer {
    margin-top: 15px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.edit-btn{
    padding: 8px 25px;
    border: none;
    font-size: 13px;
    border-radius: 6px;
    cursor: pointer;
    
}

.edit-btn {
    background: #27ae60;
    color: #fff;
}
.edit-btn:hover { background: #145A32; }

 

/* Mobile */
@media(max-width: 480px) {
    .subheader  {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
}


/* user popup */

/* ===== MODAL BACKGROUND ===== */
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
    border-radius: 12px;
    padding: 20px;
    animation: fadeIn 0.3s ease;
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

.close-btn {
    font-size: 22px;
    cursor: pointer;
}

/* ===== FORM ===== */
.form-group {
    margin-bottom: 12px;
}

.form-group label {
    display: block;
    font-size: 13px;
    margin-bottom: 4px;
    font-weight: 600;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 8px;
    font-size: 13px;
    border-radius: 6px;
    border: 1px solid #ccc;
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
    padding: 7px 16px;
    border-radius: 6px;
    cursor: pointer;
}

.cancel-btn {
    background: #ccc;
    border: none;
    padding: 7px 16px;
    border-radius: 6px;
    cursor: pointer;
}

.error-msg {
    color: red;
    font-size: 12px;
}



</style>

<div class="subheader">
    <h2 style="font-size: 25px;">User Management</h2>
    <button class="add-btn" onclick="openModal()">Add User</button>
</div>

<div class="user-grid">

    @forelse($users as $user)
        <div class="user-card">
            <div class="card-top">
                <img src="{{ asset('Image/user_icon.png') }}" class="avatarUser" alt="User Avatar">

                <div class="info">
                    <h3>{{ $user->name }}</h3>

                    <h3 style="font-size: 14px; font-weight: 500; color: #000; margin: 4px 0;">
                        {{ $user->role->name ?? 'No Role' }}
                    </h3>

                    <p>{{ $user->email }}</p>

                    <span class="status {{ $user->is_active ?? 1 ? 'active' : 'inactive' }}">
                        {{ ($user->is_active ?? 1) ? 'ACTIVE' : 'INACTIVE' }}
                    </span>
                </div>
            </div>

           <div class="card-footer">
    <button class="edit-btn"
        onclick="openEditModal(
            {{ $user->id }},
            '{{ $user->name }}',
            '{{ $user->email }}',
            '{{ $user->role_id }}'
        )">
        Edit
    </button>
</div>

        </div>
    @empty
        <p style="padding:20px;">No users found</p>
    @endforelse

</div>



<div id="addUserModal" class="modal">
    <div class="modal-content">

        <div class="modal-header">
            <h3>Add User</h3>
            <span class="close-btn" onclick="closeModal()">×</span>
        </div>

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="form-group">
                <label>User Name</label>
                <input type="text" name="name" placeholder="Enter user name" value="{{ old('name') }}">
                @error('name')
                    <span class="error-msg validation-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter email" value="{{ old('email') }}">
                @error('email')
                    <span class="error-msg validation-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password">
                @error('password')
                    <span class="error-msg validation-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" placeholder="Enter password">
            </div>

            <div class="form-group">
    <label>Role</label>
    <select name="role_id" class="form-control">
        <option value="">-- Select Role --</option>

        @foreach($roles as $role)
            <option value="{{ $role->id }}"
                {{ old('role_id') == $role->id ? 'selected' : '' }}>
                {{ $role->name }}
            </option>
        @endforeach
    </select>

    @error('role_id')
        <span class="error-msg validation-error">{{ $message }}</span>
    @enderror
</div>


            <div class="modal-footer">
                <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="save-btn">Save</button>
            </div>

        </form>
    </div>
</div>



<script>
    function openEditModal(id, name, email, role_id) {
    // Fill the inputs in the same Add User modal
    document.querySelector('#addUserModal input[name="name"]').value = name;
    document.querySelector('#addUserModal input[name="email"]').value = email;
    document.querySelector('#addUserModal select[name="role_id"]').value = role_id;

    // Change form action to update route
    const form = document.querySelector('#addUserModal form');
    form.action = '/users/update/' + id;

    // Optionally change button text and modal title
    document.querySelector('#addUserModal h3').innerText = 'Edit User';
    document.querySelector('#addUserModal button.save-btn').innerText = 'Update';

    // Show the modal
    document.getElementById('addUserModal').style.display = 'flex';
}

function openModal() {
    // Reset form to Add mode
    const form = document.querySelector('#addUserModal form');
    form.action = "{{ route('users.store') }}"; // back to store route
    document.querySelector('#addUserModal h3').innerText = 'Add User';
    document.querySelector('#addUserModal button.save-btn').innerText = 'Save';

    // // Clear all input fields
    // form.reset();

    // // Remove validation errors
    // document.querySelectorAll('.validation-error').forEach(el => el.remove());

    // document.getElementById('addUserModal').style.display = 'flex';
    document.getElementById('addUserModal').style.display = 'flex';
}



function closeModal() {
    document.getElementById('addUserModal').style.display = 'none';

    // ✅ Remove validation messages
    document.querySelectorAll('.validation-error').forEach(el => el.remove());

    // ✅ Clear form fields
    document.querySelectorAll('#addUserModal input, #addUserModal select').forEach(el => {
        if (el.type !== 'hidden') el.value = '';
    });
}
</script>

@if ($errors->any())
<script>
    document.addEventListener("DOMContentLoaded", function () {
        openModal();
    });
</script>
@endif



@if(session('success'))
<script>
    alert("{{ session('success') }}");
</script>
@endif

@endsection

