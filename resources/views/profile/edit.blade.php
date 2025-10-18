@extends('layouts.app')

@section('title', 'Profile')
@section('header', 'Profile')

@section('content')
<div style="max-width: 700px; margin: 0 auto; background: #fff; padding: 2rem; border-radius: 10px; box-shadow: 0 3px 8px rgba(0,0,0,0.1);">
    {{-- Profile Information --}}
    <h2 style="color:#333; margin-bottom:1rem;">Profile Information</h2>
    <p style="color:#555; margin-bottom:1.5rem;">
        Update your account's profile information, email address, and NIP.
    </p>
    <div class="form-section">
        @include('profile.partials.update-profile-information-form')
    </div>

    <hr style="margin:2rem 0; border:0; border-top:1px solid #ddd;">

    {{-- Update Password --}}
    <h2 style="color:#333; margin-bottom:1rem;">Update Password</h2>
    <p style="color:#555; margin-bottom:1.5rem;">
        Ensure your account is using a long, random password to stay secure.
    </p>
    <div class="form-section">
        @include('profile.partials.update-password-form')
    </div>

    <hr style="margin:2rem 0; border:0; border-top:1px solid #ddd;">

    {{-- Delete Account --}}
    <h2 style="color:#c00; margin-bottom:1rem;">Delete Account</h2>
    <p style="color:#555; margin-bottom:1.5rem;">
        Once your account is deleted, all of its resources and data will be permanently deleted.
        Before deleting your account, please download any data or information that you wish to retain.
    </p>
    <div class="form-section">
        @include('profile.partials.delete-user-form')
    </div>
</div>

<style>
.form-section form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    max-width: 500px;
}

.form-section label {
    font-weight: bold;
    color: #333;
    margin-bottom: 0.2rem;
}

.form-section input[type="text"],
.form-section input[type="email"],
.form-section input[type="password"] {
    width: 100%;
    padding: 0.6rem;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 0.95rem;
    box-sizing: border-box;
    transition: all 0.2s ease;
}

.form-section input:focus {
    border-color: #3490dc;
    box-shadow: 0 0 3px rgba(52,144,220,0.4);
    outline: none;
}

.form-section button {
    align-self: flex-start;
    background-color: #3490dc;
    color: white;
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background 0.2s;
}

.form-section button:hover {
    background-color: #2779bd;
}
</style>
@endsection
