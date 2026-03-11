@extends('Layout.client')
@section('content')

<style>
.reset-wrap {
    background: linear-gradient(135deg, #EFF0F5, #EFF0F5);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 70vh;
    padding: 20px;
}

.reset-wrap .form-container {
    background: #fff;
    width: 100%;
    max-width: 420px;
    padding: 40px 35px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.reset-wrap .brand {
    text-align: center;
    margin-bottom: 6px;
}

.reset-wrap .brand span {
    color: #CE3A32;
    font-size: 22px;
    font-weight: bold;
    font-family: 'Pacifico', cursive;
}

.reset-wrap h2 {
    text-align: center;
    font-size: 22px;
    color: #222;
    margin: 0 0 6px;
}

.reset-wrap .form-desc {
    text-align: center;
    font-size: 13px;
    color: #888;
    margin-bottom: 25px;
    line-height: 1.5;
}

.reset-wrap input[type="password"] {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ddd;
    outline: none;
    transition: 0.3s;
    box-sizing: border-box;
    font-size: 14px;
    background: #fafafa;
    color: #333;
    display: block;
}

.reset-wrap input[type="password"]:focus {
    border-color: #1f77d0;
    background: #fff;
}

.reset-wrap .primary-btn {
    width: 100%;
    padding: 13px;
    background: #1f77d0;
    border: none;
    border-radius: 8px;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
    display: block;
    margin-top: 5px;
}

.reset-wrap .primary-btn:hover {
    background: #155fa0;
}

.reset-wrap .back-link {
    text-align: center;
    font-size: 14px;
    margin-top: 18px;
    color: #666;
}

.reset-wrap .back-link a {
    color: #1f77d0;
    font-weight: bold;
    text-decoration: none;
}

.reset-wrap .back-link a:hover {
    text-decoration: underline;
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
    font-size: 14px;
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
    font-size: 14px;
}
</style>

<div class="reset-wrap">
    <div class="form-container">

        <div class="brand"><span>SMart Ecom</span></div>
        <h2>Reset Password</h2>
        <p class="form-desc">Enter your new password below.</p>

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('client.reset.save') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <input type="password" name="password"
                   placeholder="New Password" required>
            <input type="password" name="password_confirmation"
                   placeholder="Confirm New Password" required>

            <button type="submit" class="primary-btn">
                Reset Password
            </button>
        </form>

        <p class="back-link">
            Remember password?
            <a href="{{ route('ClientLogin') }}">Login</a>
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