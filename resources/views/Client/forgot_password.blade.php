@extends('Layout.client')
@section('content')

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

@endsection