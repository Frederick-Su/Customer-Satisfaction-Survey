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
                <button type="submit" class="admin-link admin-link-button">Log out</button>
            </form>
        </div>
    </header>

    <!-- Summary Metrics Bar -->
    <div class="admin-summary-grid">
        <div class="summary-card">
            <span class="summary-label">Total Respons</span>
            <span class="summary-value">{{ number_format($totalRows, 0, ',', '.') }}</span>
        </div>
        <div class="summary-card">
            <span class="summary-label">Pelanggan Unik</span>
            <span class="summary-value">{{ number_format($uniqueCustomers, 0, ',', '.') }}</span>
        </div>
        <div class="summary-card">
            <span class="summary-label">Kepuasan Rata-rata</span>
            <span class="summary-value {{ ($meanKepuasan ?? 0) <= 2.00 ? 'is-danger' : '' }}">{{ number_format($meanKepuasan ?? 0, 2) }} / 5.00</span>
        </div>
    </div>

    <div class="admin-summary-modes">
        <div class="summary-mode-group">
            <span class="summary-group-title">Modus Teknisi</span>
            <div class="summary-mode-items">
                @foreach ($teknisiColumns as $col)
                    <div>
                        <span class="summary-mode-key">{{ Str::of($col)->after('teknisi_')->replace('_', ' ')->title() }}:</span>
                        <span class="summary-mode-val">{{ $choiceLabels[$col][$modes[$col]] ?? '—' }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="summary-mode-group">
            <span class="summary-group-title">Modus Sales</span>
            <div class="summary-mode-items">
                @foreach ($salesColumns as $col)
                    <div>
                        <span class="summary-mode-key">{{ Str::of($col)->after('sales_')->replace('_', ' ')->title() }}:</span>
                        <span class="summary-mode-val">{{ $choiceLabels[$col][$modes[$col]] ?? '—' }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <form class="admin-filters" method="GET" action="{{ route('admin.responses.index') }}">
        <div class="admin-filter-search">
            <label for="q">Cari respons</label>
            <input id="q" name="q" type="search" value="{{ $filters['q'] ?? '' }}" placeholder="ID pelanggan atau saran">
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
        <button type="submit" class="admin-button">Filter</button>
        <a class="admin-reset" href="{{ route('admin.responses.index') }}">Reset</a>
    </form>

    <div class="admin-summary">
        <strong>{{ number_format($responses->total(), 0, ',', '.') }}</strong> respons ditemukan
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
            <tr>
                <th scope="col" rowspan="2">Tanggal</th>
                <th scope="col" rowspan="2">Pelanggan</th>
                <th scope="col" colspan="5">Teknisi</th>
                <th scope="col" colspan="4">Sales</th>
                <th scope="col" rowspan="2">Kepuasan</th>
                <th scope="col" rowspan="2">Saran</th>
            </tr>
            <tr>
                {{-- Teknisi Subcolumns --}}
                <th scope="col">Jadwal</th>
                <th scope="col">Instalasi</th>
                <th scope="col">Penampilan</th>
                <th scope="col">Panduan</th>
                <th scope="col">Sikap</th>
                {{-- Sales Subcolumns --}}
                <th scope="col">Penjelasan</th>
                <th scope="col">Bantuan</th>
                <th scope="col">Respons</th>
                <th scope="col">Sikap</th>
            </tr>
            </thead>
            <tbody>
                @forelse ($responses as $response)
                    <tr>
                        <td class="admin-date">
                            {{ $response->created_at->format('d M Y') }}<br>
                            <span>{{ $response->created_at->format('H:i') }}</span>
                        </td>
                        <td>
                            <strong>{{ $response->customer_id ?: 'Anonim' }}</strong>
                        </td>
                        <td>{{ $choiceLabels['teknisi_jadwal'][$response->teknisi_jadwal] ?? '—' }}</td>
                        <td>{{ $choiceLabels['teknisi_kualitas_instalasi'][$response->teknisi_kualitas_instalasi] ?? '—' }}</td>
                        <td>{{ $choiceLabels['teknisi_penampilan'][$response->teknisi_penampilan] ?? '—' }}</td>
                        <td>{{ $choiceLabels['teknisi_panduan'][$response->teknisi_panduan] ?? '—' }}</td>
                        <td>{{ $choiceLabels['teknisi_sikap'][$response->teknisi_sikap] ?? '—' }}</td>
                        <td>{{ $choiceLabels['sales_penjelasan'][$response->sales_penjelasan] ?? '—' }}</td>
                        <td>{{ $choiceLabels['sales_bantuan'][$response->sales_bantuan] ?? '—' }}</td>
                        <td>{{ $choiceLabels['sales_respons'][$response->sales_respons] ?? '—' }}</td>
                        <td>{{ $choiceLabels['sales_sikap'][$response->sales_sikap] ?? '—' }}</td>
                        <td>
                            <span class="admin-rating {{ $response->kepuasan_keseluruhan <= 2 ? 'is-danger' : '' }}">
                                {{ $response->kepuasan_keseluruhan }}/5
                            </span>
                        </td>
                        <td class="admin-feedback">{{ $response->saran ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="admin-empty" colspan="13">Tidak ada respons yang cocok dengan filter ini.</td>
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