@extends('layouts.survey')

@section('title', 'Terima Kasih')

@section('content')
<div class="shell thanks-card">

    <div class="brandmark">
        <img src="{{ asset('images/vnet-logo.png') }}" alt="VNET" class="brandmark-logo" style="width: 25%; height: auto;">
    </div>

    <h1>Terima kasih atas masukan Anda</h1>
    <p>Jawaban Anda sudah kami terima dan akan digunakan untuk meningkatkan kualitas layanan teknisi dan sales VNET.</p>
    <p>Anda dapat menutup halaman ini kapan saja.</p>

    <a href="{{ route('survey.create') }}" class="back-link">Isi survei lain</a>
</div>
@endsection
