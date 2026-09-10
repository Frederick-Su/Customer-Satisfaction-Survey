@extends('layouts.survey')

@section('title', 'Admin VNET')

@section('content')
<div class="admin-login-shell">
    <div class="admin-login-box">
        <div class="brandmark">VNET / ADMIN</div>
        <h1>Masuk ke respons survei</h1>
        <p class="admin-login-intro">Gunakan akun internal untuk melihat masukan pelanggan.</p>

        @if ($errors->any())
            <div class="error-summary">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf
            <div class="field">
                <label for="username">Nama pengguna</label>
                <input id="username" name="username" type="text" value="{{ old('username') }}" autocomplete="username" required autofocus>
            </div>
            <div class="field">
                <label for="password">Kata sandi</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required>
            </div>
            <label class="admin-remember"><input type="checkbox" name="remember"> Ingat saya</label>
            <button type="submit" class="submit">Masuk</button>
        </form>
    </div>
</div>
@endsection