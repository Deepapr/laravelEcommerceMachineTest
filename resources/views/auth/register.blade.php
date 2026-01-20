@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="card" style="max-width: 400px; margin: 3rem auto;">
    <h2>Register</h2>
    <form action="{{ route('auth.register') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <span style="color: #e74c3c;">{{ $message }}</span>
            @enderror
        </div>

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

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn" style="width: 100%;">Register</button>
    </form>

    <p style="margin-top: 1rem; text-align: center;">
        Already have an account? <a href="{{ route('login') }}">Login here</a>
    </p>
</div>
@endsection
