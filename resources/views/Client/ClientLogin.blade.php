@extends('Layout.client')


@section('content')

<style>
 

.Loginform{
    background:linear-gradient(135deg,#EFF0F5,#EFF0F5);
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:70vh;
    padding:20px;
}

/* Main Box */
.form-container{
    background:#fff;
    width:100%;
    max-width:400px;
    padding:30px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.30);
    position:relative;
}

/* Form Sections */
.form{
    display:none;
    animation:fade 0.3s ease-in-out;
}

.form.active{
    display:block;
}

@keyframes fade{
    from{opacity:0; transform:translateX(10px);}
    to{opacity:1; transform:translateX(0);}
}

h2{
    text-align:center;
    margin-bottom:20px;
}

/* Inputs */
input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border-radius:8px;
    border:1px solid #ccc;
    outline:none;
    transition:0.3s;
}

input:focus{
    border-color:#4e73df;
}

/* Button */
.primary-btn{
    width:100%;
    padding:12px;
    background:#1f77d0;
    border:none;
    border-radius:8px;
    color:#fff;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}

.primary-btn:hover{
    background:#155fa0;
}

/* Text */
p{
    text-align:center;
    font-size:14px;
    margin-top:15px;
}

.toggle-btn{
    color:#4e73df;
    font-weight:bold;
    cursor:pointer;
}

.link{
    display:block;
    text-align:right;
    font-size:13px;
    margin-bottom:15px;
    color:#4e73df;
    text-decoration:none;
}

/* Responsive */
@media(max-width:480px){
    .form-container{
        padding:20px;
    }
}

.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 15px;
    text-align: center;
    font-weight: 500;
    border: 1px solid #c3e6cb;
}
</style>


<div class="Loginform">
<div class="form-container">

    <!-- LOGIN FORM -->
    <div class="form login-form active">
        <h2>Login</h2>
      @if(session('success'))
    <div class="alert-success" id="successMessage">
        {{ session('success') }}
    </div>
@endif
        <input type="email" placeholder="Email">
        <input type="password" placeholder="Password">

        <a href="#" class="link">Forgot password?</a>

        <button class="primary-btn">Login</button>

        <p>
            Don't have an account?
            <span class="toggle-btn" onclick="showSignup()">Signup</span>
        </p>
    </div>

    <!-- SIGNUP FORM -->
    <div class="form signup-form">
        <h2>Signup</h2>

        <form method="POST" action="{{ route('client.register') }}">
    @csrf

    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="mobile" placeholder="Phone Number" required>
    <input type="password" name="password" placeholder="Create Password" required>
    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

    <button type="submit" class="primary-btn">Signup</button>
</form>

        <p>
            Already have an account?
            <span class="toggle-btn" onclick="showLogin()">Login</span>
        </p>
    </div>

</div>
</div>


<script>
function showSignup(){
    document.querySelector('.login-form').classList.remove('active');
    document.querySelector('.signup-form').classList.add('active');
}

function showLogin(){
    document.querySelector('.signup-form').classList.remove('active');
    document.querySelector('.login-form').classList.add('active');
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Only switch to login form if registration was successful
    @if(session('success'))
        document.querySelector('.signup-form').classList.remove('active');
        document.querySelector('.login-form').classList.add('active');   
    @endif
});
</script>

<script>
setTimeout(function () {
    let message = document.getElementById('successMessage');
    if (message) {
        message.style.transition = "opacity 0.5s";
        message.style.opacity = "0";
        setTimeout(() => message.remove(), 500);
    }
}, 3000);
</script>

@endsection