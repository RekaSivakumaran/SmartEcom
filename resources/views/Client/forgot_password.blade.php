@extends('Layout.client')
@section('content')

<style>
.Loginform {
    background: linear-gradient(135deg, #EFF0F5, #EFF0F5);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 40vh;
    padding: 20px;
}

.Loginform .form-container {
    background: #fff;
    width: 100%;
    max-width: 400px;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.30);
}

.Loginform h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
}

.Loginform input[type="email"],
.Loginform input[type="password"],
.Loginform input[type="text"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
    outline: none;
    transition: 0.3s;
    box-sizing: border-box;
    display: block;
    font-size: 14px;
    background: #fff;
    color: #333;
}

.Loginform input[type="email"]:focus,
.Loginform input[type="password"]:focus {
    border-color: #4e73df;
}

.Loginform .primary-btn {
    width: 100%;
    padding: 12px;
    background: #1f77d0;
    border: none;
    border-radius: 8px;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
    display: block;
}

.Loginform .primary-btn:hover {
    background: #155fa0;
}

.Loginform p {
    text-align: center;
    font-size: 14px;
    margin-top: 15px;
    color: #555;
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

.alert-error {
    background: #f8d7da;
    color: #721c24;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 15px;
    text-align: center;
    font-weight: 500;
    border: 1px solid #f5c6cb;
}
</style>

<div class="Loginform">
    <div class="form-container">
        <h2>Forgot Password</h2>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('client.forgot.send') }}">
            @csrf
            <input type="email" name="email"
                   placeholder="Enter your email" required>
            <button type="submit" class="primary-btn">
                Send Reset Link
            </button>
        </form>

        <p>
            Remember password?
            <a href="{{ route('ClientLogin') }}"
               style="color:#4e73df; font-weight:bold;">Login</a>
        </p>
    </div>
</div>

<script>
setTimeout(() => {
    document.querySelectorAll('.alert-success, .alert-error')
        .forEach(msg => {
            msg.style.transition = "opacity 0.5s";
            msg.style.opacity = "0";
            setTimeout(() => msg.remove(), 500);
        });
}, 3000);
</script>

@endsection