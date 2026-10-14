{{--
    Summary card for one question group (Teknisi / Sales).

    Expects:
      $key         string  short id, used for DOM ids + remembering open/closed state ("teknisi")
      $title       string  card heading
      $columns     array   column names in this group
      $columnStats array   built in AdminResponseController (total, top, breakdown per column)
--}}
@php
    $prefix = $key . '_';
@endphp

<div class="summary-mode-group" data-summary-group="{{ $key }}">
    <div class="summary-group-header">
        <span class="summary-group-title">{{ $title }}</span>
        <button type="button"
                class="summary-toggle"
                data-summary-toggle
                aria-expanded="false"
                aria-controls="summary-compact-{{ $key }} summary-detail-{{ $key }}">
            <span>Detail</span>
            <svg class="summary-toggle-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
    </div>

    {{-- Collapsed view: most common answer + its share and count --}}
    <div class="summary-mode-items" id="summary-compact-{{ $key }}" data-summary-compact>
        @foreach ($columns as $col)
            @php
                $stat = $columnStats[$col];
                $top = $stat['top'] !== null ? $stat['breakdown'][$stat['top']] : null;
            @endphp
            <div class="summary-mode-row">
                <span class="summary-mode-key">{{ Str::of($col)->after($prefix)->replace('_', ' ')->title() }}:</span>
                <span class="summary-mode-val">{{ $top['label'] ?? '—' }}</span>
                @if ($top)
                    <span class="summary-mode-stat">
                        {{ number_format($top['percent'], 1, ',', '.') }}% · {{ number_format($top['count'], 0, ',', '.') }}
                    </span>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Expanded view: one mini card per question, every answer option listed --}}
    <div class="summary-detail-grid" id="summary-detail-{{ $key }}" data-summary-detail hidden>
        @foreach ($columns as $col)
            @php
                $stat = $columnStats[$col];
            @endphp
            <div class="summary-detail-card">
                <div class="summary-detail-head">
                    <span class="summary-detail-title">{{ Str::of($col)->after($prefix)->replace('_', ' ')->title() }}</span>
                    <span class="summary-detail-total">{{ number_format($stat['total'], 0, ',', '.') }} respons</span>
                </div>

                <ul class="summary-detail-list">
                    @foreach ($stat['breakdown'] as $value => $row)
                        <li class="{{ $value === $stat['top'] ? 'is-top' : '' }}">
                            <div class="summary-detail-row">
                                <span class="summary-detail-label">{{ $row['label'] }}</span>
                                <span class="summary-detail-figures">
                                    <strong>{{ number_format($row['percent'], 1, ',', '.') }}%</strong>
                                    <span>{{ number_format($row['count'], 0, ',', '.') }}</span>
                                </span>
                            </div>
                            <div class="summary-bar" role="presentation">
                                <span style="width: {{ $row['percent'] }}%"></span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>