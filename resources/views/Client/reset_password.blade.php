@extends('Layout.client')
@section('content')

<div class="Loginform">
    <div class="form-container">
        <h2>Reset Password</h2>

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
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
    </div>
</div>

@endsection