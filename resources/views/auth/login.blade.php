@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="card" style="max-width: 400px; margin: 3rem auto;">
    <h2>Login</h2>
    <form action="{{ route('auth.login') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <span style="color: #e74c3c;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            @error('password')
                <span style="color: #e74c3c;">{{ $message }}</span>
            @enderror
        </div>

        <div class="checkbox-group">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember" style="display: inline; margin: 0;">Remember me</label>
        </div>

        <button type="submit" class="btn" style="width: 100%;">Login</button>
    </form>

    <p style="margin-top: 1rem; text-align: center;">
        Don't have an account? <a href="{{ route('register') }}">Register here</a>
    </p>
</div>
@endsection
