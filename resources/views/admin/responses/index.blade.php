@extends('layouts.survey')

@section('title', 'Respons Survei')

@section('content')
<div class="admin-shell">
    <header class="admin-header">
        <div>
            <div class="brandmark">VNET / ADMIN</div>
            <h1>Respons survei</h1>
            <p>Telusuri masukan pelanggan dan pantau kepuasan layanan.</p>
        </div>
        <div class="admin-header-actions">
            <a class="admin-link" href="{{ route('survey.create') }}">Lihat survei</a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="admin-link admin-link-button">Keluar</button>
            </form>
        </div>
    </header>

    <form class="admin-filters" method="GET" action="{{ route('admin.responses.index') }}">
        <div class="admin-filter-search">
            <label for="q">Cari respons</label>
            <input id="q" name="q" type="search" value="{{ $filters['q'] ?? '' }}" placeholder="Nama, nomor HP, atau saran">
        </div>
        <div>
            <label for="rating">Kepuasan</label>
            <select id="rating" name="rating">
                <option value="">Semua nilai</option>
                @for ($rating = 5; $rating >= 1; $rating--)
                    <option value="{{ $rating }}" @selected((string) ($filters['rating'] ?? '') === (string) $rating)>{{ $rating }} dari 5</option>
                @endfor
            </select>
        </div>
        <div>
            <label for="from">Dari tanggal</label>
            <input id="from" name="from" type="date" value="{{ $filters['from'] ?? '' }}">
        </div>
        <div>
            <label for="to">Sampai tanggal</label>
            <input id="to" name="to" type="date" value="{{ $filters['to'] ?? '' }}">
        </div>
        <button type="submit" class="admin-button">Terapkan</button>
        <a class="admin-reset" href="{{ route('admin.responses.index') }}">Reset</a>
    </form>

    <div class="admin-summary">
        <strong>{{ number_format($responses->total(), 0, ',', '.') }}</strong> respons ditemukan
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Pelanggan</th>
                    <th scope="col">Teknisi</th>
                    <th scope="col">Sales</th>
                    <th scope="col">Kepuasan</th>
                    <th scope="col">Saran</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($responses as $response)
                    <tr>
                        <td class="admin-date">{{ $response->created_at->format('d M Y') }}<br><span>{{ $response->created_at->format('H:i') }}</span></td>
                        <td>
                            <strong>{{ $response->nama ?: 'Anonim' }}</strong>
                            @if ($response->no_hp)
                                <span class="admin-muted">{{ $response->no_hp }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="admin-value">{{ $choiceLabels['teknisi_kualitas_instalasi'][$response->teknisi_kualitas_instalasi] }}</span>
                            <span class="admin-muted">Sikap: {{ $choiceLabels['teknisi_sikap'][$response->teknisi_sikap] }}</span>
                        </td>
                        <td>
                            <span class="admin-value">{{ $choiceLabels['sales_penjelasan'][$response->sales_penjelasan] }}</span>
                            <span class="admin-muted">Respons: {{ $choiceLabels['sales_respons'][$response->sales_respons] }}</span>
                        </td>
                        <td><span class="admin-rating admin-rating-{{ $response->kepuasan_keseluruhan }}">{{ $response->kepuasan_keseluruhan }}/5</span></td>
                        <td class="admin-feedback">{{ $response->saran ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="admin-empty" colspan="6">Tidak ada respons yang cocok dengan filter ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($responses->hasPages())
        <nav class="admin-pagination" aria-label="Navigasi halaman">
            {{ $responses->links() }}
        </nav>
    @endif
</div>
@endsection