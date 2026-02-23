@extends('Layout.client')

@section('content')
<div class="delivery-container">
    <h2>Delivery Information</h2>

    <div class="form-grid">

        <div class="form-group">
            <label>Full name</label>
            <input type="text" placeholder="Enter your first and last name">
        </div>

        <div class="form-group">
            <label>Province</label>
            <select>
                <option>Please choose your province</option>
            </select>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" placeholder="Please enter your phone number">
        </div>

        <div class="form-group">
            <label>District</label>
            <select>
                <option>Please choose your district</option>
            </select>
        </div>

        <div class="form-group">
            <label>Building / House No / Floor / Street</label>
            <input type="text" placeholder="Please enter">
        </div>

        <div class="form-group">
            <label>City</label>
            <select>
                <option>Please choose your city</option>
            </select>
        </div>

        <div class="form-group">
            <label>Colony / Suburb / Locality / Landmark</label>
            <input type="text" placeholder="Please enter">
        </div>

        <div class="form-group">
            <label>Address</label>
            <input type="text" placeholder="For Example: House# 123, Street# 123, ABC Road">
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" placeholder="Please enter your email">
        </div>

    </div>

    <div class="label-section">
        <p>Select a label for effective delivery:</p>

        <div class="label-buttons">
            <button class="label-btn office">🏢 OFFICE</button>
            <button class="label-btn home">🏠 HOME</button>
        </div>
    </div>

    <button class="save-btn">SAVE ADDRESS</button>

</div>
<style>
.delivery-container{
    max-width:1000px;
    margin:40px auto;
    background:#fff;
    padding:30px;
    border-radius:6px;
}

h2{
    margin-bottom:25px;
    font-weight:600;
}

.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px 40px;
}

.form-group{
    display:flex;
    flex-direction:column;
}

label{
    margin-bottom:8px;
    font-size:14px;
    font-weight:500;
}

input, select{
    padding:12px;
    border:1px solid #ddd;
    border-radius:4px;
    font-size:14px;
}

input:focus, select:focus{
    outline:none;
    border-color:#4e73df;
}

/* Label section */
.label-section{
    margin-top:30px;
}

.label-buttons{
    margin-top:15px;
    display:flex;
    gap:20px;
}

.label-btn{
    padding:12px 25px;
    border-radius:8px;
    border:2px solid #ccc;
    background:#f8f9fa;
    cursor:pointer;
    font-weight:600;
    transition:0.3s;
}

.label-btn.office{
    border-color:#17a2b8;
}

.label-btn.home{
    border-color:#dc3545;
}

.label-btn:hover{
    background:#e9ecef;
}

/* Save button */
.save-btn{
    margin-top:30px;
    padding:14px 30px;
    background:#17a2b8;
    color:#fff;
    border:none;
    border-radius:4px;
    font-size:16px;
    cursor:pointer;
}

.save-btn:hover{
    background:#138496;
}

/* Responsive */
@media(max-width:768px){
    .form-grid{
        grid-template-columns:1fr;
    }
}
</style>
@endsection