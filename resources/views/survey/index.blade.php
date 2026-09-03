@extends('layouts.survey')

@section('title', 'Survei Kepuasan Pelanggan')

@section('content')

@php
    $sections = [
        'A' => [
            'title' => 'Layanan Teknisi',
            'desc' => 'Tentang kedatangan dan pekerjaan teknisi saat pemasangan.',
            'questions' => [
                [
                    'name' => 'teknisi_jadwal',
                    'label' => 'Apakah teknisi datang sesuai jadwal yang telah disepakati?',
                    'options' => ['ya' => 'Ya', 'tidak' => 'Tidak'],
                ],
                [
                    'name' => 'teknisi_kualitas_instalasi',
                    'label' => 'Bagaimana kualitas dan kerapian instalasi yang dilakukan teknisi?',
                    'options' => ['baik' => 'Baik', 'cukup' => 'Cukup', 'kurang_baik' => 'Kurang Baik'],
                ],
                [
                    'name' => 'teknisi_penampilan',
                    'label' => 'Apakah teknisi berpenampilan rapi dan sopan?',
                    'options' => ['ya' => 'Ya', 'tidak' => 'Tidak'],
                ],
                [
                    'name' => 'teknisi_panduan',
                    'label' => 'Apakah teknisi memberikan panduan penggunaan layanan dengan jelas?',
                    'options' => ['ya' => 'Ya', 'tidak' => 'Tidak'],
                ],
                [
                    'name' => 'teknisi_sikap',
                    'label' => 'Bagaimana sikap dan pelayanan teknisi selama proses instalasi?',
                    'options' => ['sangat_baik' => 'Sangat Baik', 'baik' => 'Baik', 'cukup' => 'Cukup', 'kurang_baik' => 'Kurang Baik'],
                ],
            ],
        ],
        'B' => [
            'title' => 'Sales',
            'desc' => 'Tentang proses penjelasan produk hingga penjadwalan pemasangan.',
            'questions' => [
                [
                    'name' => 'sales_penjelasan',
                    'label' => 'Apakah sales menjelaskan produk, paket layanan, dan pembayaran dengan jelas?',
                    'options' => ['jelas' => 'Jelas', 'cukup_jelas' => 'Cukup Jelas', 'tidak_jelas' => 'Tidak Jelas'],
                ],
                [
                    'name' => 'sales_bantuan',
                    'label' => 'Apakah sales membantu proses pendaftaran hingga penjadwalan pemasangan?',
                    'options' => ['sangat_membantu' => 'Sangat Membantu', 'cukup_membantu' => 'Cukup Membantu', 'tidak_membantu' => 'Tidak Membantu'],
                ],
                [
                    'name' => 'sales_respons',
                    'label' => 'Bagaimana respons sales dalam menjawab pertanyaan Anda?',
                    'options' => ['sangat_responsif' => 'Sangat Responsif', 'cukup_responsif' => 'Cukup Responsif', 'lambat' => 'Lambat'],
                ],
                [
                    'name' => 'sales_sikap',
                    'label' => 'Bagaimana sikap dan pelayanan sales secara keseluruhan?',
                    'options' => ['sangat_baik' => 'Sangat Baik', 'baik' => 'Baik', 'cukup' => 'Cukup', 'kurang_baik' => 'Kurang Baik'],
                ],
            ],
        ],
    ];
@endphp

<div class="progress-rail" aria-hidden="true"><div class="progress-fill"></div></div>

<div class="shell">

    <div class="brandmark">
        <img src="{{ asset('images/vnet-logo.png') }}" alt="VNET" class="brandmark-logo" style="width: 25%; height: auto;">
    </div>

    <div class="page-header">
        <h1>Survei Kepuasan Pelanggan</h1>
        <p>Ceritakan pengalaman Anda saat pemasangan dan proses berlangganan — masukan Anda membantu kami meningkatkan layanan.</p>
        <div class="meta">sekitar 2 menit &middot; 13 pertanyaan</div>
    </div>

    <div class="pulse-divider"><span class="node"></span></div>

    @if ($errors->any())
        <div class="error-summary">
            <strong>Ada beberapa pertanyaan yang belum terisi</strong>
            Silakan periksa kembali bagian yang ditandai di bawah.
        </div>
    @endif

    <form class="survey-form" method="POST" action="{{ route('survey.store') }}" novalidate>
        @csrf

        <div class="section">
            <div class="section-head">
                <div>
                    <h2 style="margin-top:0">Identitas</h2>
                    <p>Opsional — hanya digunakan jika kami perlu menindaklanjuti masukan Anda.</p>
                </div>
            </div>

            <div class="field">
                <label for="nama">Nama <span class="hint">(opsional)</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" placeholder="Nama Anda">
            </div>
            <div class="field">
                <label for="no_hp">Nomor HP / ID Pelanggan <span class="hint">(opsional)</span></label>
                <input type="tel" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
            </div>
        </div>

        @foreach ($sections as $letter => $section)
            <section class="section">
                <div class="section-head">
                    <span class="section-badge">{{ $letter }}</span>
                    <div>
                        <h2>{{ $section['title'] }}</h2>
                        <p>{{ $section['desc'] }}</p>
                    </div>
                </div>

                @foreach ($section['questions'] as $q)
                    <div class="question @error($q['name']) has-error @enderror" role="group" aria-labelledby="{{ $q['name'] }}_label">
                        <p class="q-label" id="{{ $q['name'] }}_label">{{ $q['label'] }}</p>
                        <div class="pill-group">
                            @foreach ($q['options'] as $value => $optionLabel)
                                <input
                                    type="radio"
                                    name="{{ $q['name'] }}"
                                    id="{{ $q['name'] }}_{{ $value }}"
                                    value="{{ $value }}"
                                    {{ old($q['name']) === $value ? 'checked' : '' }}
                                >
                                <label for="{{ $q['name'] }}_{{ $value }}">{{ $optionLabel }}</label>
                            @endforeach
                        </div>
                        @error($q['name'])
                            <span class="q-error">{{ $message }}</span>
                        @enderror
                    </div>
                @endforeach
            </section>
        @endforeach

        <section class="section">
            <div class="section-head">
                <span class="section-badge">C</span>
                <div>
                    <h2>Kepuasan Keseluruhan</h2>
                    <p>Secara umum, bagaimana penilaian Anda terhadap layanan VNET?</p>
                </div>
            </div>

            <div class="question @error('kepuasan_keseluruhan') has-error @enderror" role="group" aria-labelledby="rating_label">
                <p class="q-label" id="rating_label">Berikan penilaian dari 1 sampai 5 bintang</p>
                <div class="star-rating-row">
                    <div class="star-rating">
                        @for ($i = 5; $i >= 1; $i--)
                            <input
                                type="radio"
                                name="kepuasan_keseluruhan"
                                id="rating_{{ $i }}"
                                value="{{ $i }}"
                                {{ (string) old('kepuasan_keseluruhan') === (string) $i ? 'checked' : '' }}
                            >
                            <label for="rating_{{ $i }}" aria-label="{{ $i }} dari 5 bintang">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.6 7.1.6-5.4 4.7 1.6 7-6.2-3.8L5.8 21l1.6-7L2 9.2l7.1-.6L12 2z"/></svg>
                            </label>
                        @endfor
                    </div>
                    <span class="star-rating-caption" id="rating-caption">Ketuk salah satu bintang untuk memberi nilai</span>
                </div>
                @error('kepuasan_keseluruhan')
                    <span class="q-error">{{ $message }}</span>
                @enderror
            </div>
        </section>

        <section class="section">
            <div class="section-head">
                <span class="section-badge">D</span>
                <div>
                    <h2>Masukan Anda</h2>
                    <p>Ceritakan hal yang menurut Anda perlu kami tingkatkan.</p>
                </div>
            </div>

            <div class="field">
                <label for="saran">Apa yang perlu kami tingkatkan? <span class="hint">(opsional)</span></label>
                <textarea name="saran" id="saran" placeholder="Tulis saran, keluhan, atau hal lain yang ingin Anda sampaikan...">{{ old('saran') }}</textarea>
                @error('saran')
                    <span class="q-error">{{ $message }}</span>
                @enderror
            </div>
        </section>

        <div class="submit-row">
            <button type="submit" class="submit">Kirim Survei</button>
            <p class="submit-note">Jawaban Anda bersifat rahasia dan hanya digunakan untuk peningkatan layanan VNET.</p>
        </div>
    </form>
</div>

<script>
(function () {
    var ratingLabels = {1: 'Sangat Tidak Puas', 2: 'Tidak Puas', 3: 'Cukup Puas', 4: 'Puas', 5: 'Sangat Puas'};
    var caption = document.getElementById('rating-caption');
    var inputs = document.querySelectorAll('input[name="kepuasan_keseluruhan"]');

    inputs.forEach(function (input) {
        if (input.checked && caption) {
            caption.textContent = ratingLabels[input.value] + ' (' + input.value + ' dari 5)';
        }
        input.addEventListener('change', function () {
            if (caption) {
                caption.textContent = ratingLabels[input.value] + ' (' + input.value + ' dari 5)';
            }
        });
    });
})();
</script>

@endsection
