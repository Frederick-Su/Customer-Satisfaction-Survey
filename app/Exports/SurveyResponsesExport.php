<?php

namespace App\Exports;

use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SurveyResponsesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Request $request;
    protected array $choiceLabels;

    public function __construct(Request $request, array $choiceLabels)
    {
        $this->request = $request;
        $this->choiceLabels = $choiceLabels;
    }

    public function query()
    {
        $query = SurveyResponse::query();

        if ($this->request->filled('q')) {
            $search = $this->request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('customer_id', 'like', "%{$search}%")
                  ->orWhere('saran', 'like', "%{$search}%");
            });
        }

        if ($this->request->filled('rating')) {
            $query->where('kepuasan_keseluruhan', $this->request->input('rating'));
        }

        if ($this->request->filled('from')) {
            $query->whereDate('created_at', '>=', $this->request->input('from'));
        }

        if ($this->request->filled('to')) {
            $query->whereDate('created_at', '<=', $this->request->input('to'));
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Tanggal / Waktu',
            'ID Pelanggan',
            'Teknisi - Jadwal',
            'Teknisi - Instalasi',
            'Teknisi - Penampilan',
            'Teknisi - Panduan',
            'Teknisi - Sikap',
            'Sales - Penjelasan',
            'Sales - Bantuan',
            'Sales - Respons',
            'Sales - Sikap',
            'Kepuasan Keseluruhan',
            'Saran',
        ];
    }

    public function map($row): array
    {
        return [
            $row->created_at->format('d/m/Y H:i:s'),
            $row->customer_id ?? 'Anonim',
            $this->choiceLabels['teknisi_jadwal'][$row->teknisi_jadwal] ?? '—',
            $this->choiceLabels['teknisi_kualitas_instalasi'][$row->teknisi_kualitas_instalasi] ?? '—',
            $this->choiceLabels['teknisi_penampilan'][$row->teknisi_penampilan] ?? '—',
            $this->choiceLabels['teknisi_panduan'][$row->teknisi_panduan] ?? '—',
            $this->choiceLabels['teknisi_sikap'][$row->teknisi_sikap] ?? '—',
            $this->choiceLabels['sales_penjelasan'][$row->sales_penjelasan] ?? '—',
            $this->choiceLabels['sales_bantuan'][$row->sales_bantuan] ?? '—',
            $this->choiceLabels['sales_respons'][$row->sales_respons] ?? '—',
            $this->choiceLabels['sales_sikap'][$row->sales_sikap] ?? '—',
            $row->kepuasan_keseluruhan,
            $row->saran ?? '—',
        ];
    }
}