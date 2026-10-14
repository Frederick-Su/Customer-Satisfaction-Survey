<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Exports\SurveyResponsesExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminResponseController extends Controller
{
    /**
     * Browse submitted survey responses with optional filters.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $responses = SurveyResponse::query()
            ->when($filters['q'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('customer_id', 'like', "%{$search}%")
                        ->orWhere('saran', 'like', "%{$search}%");
                });
            })
            ->when($filters['rating'] ?? null, fn ($query, int $rating) => $query->where('kepuasan_keseluruhan', $rating))
            ->when($filters['from'] ?? null, fn ($query, string $from) => $query->whereDate('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($query, string $to) => $query->whereDate('created_at', '<=', $to));
        
        $totalRows = (clone $responses)->count();
        $uniqueCustomers = (clone $responses)->whereNotNull('customer_id')->distinct('customer_id')->count('customer_id');
        $meanKepuasan = (clone $responses)->avg('kepuasan_keseluruhan');
        $teknisiColumns = ['teknisi_jadwal', 'teknisi_kualitas_instalasi', 'teknisi_penampilan', 'teknisi_panduan', 'teknisi_sikap'];
        $salesColumns = ['sales_penjelasan', 'sales_bantuan', 'sales_respons', 'sales_sikap'];

        // Per-column distribution: count + percentage for every possible choice,
        // plus which choice is the most common ("top").
        $choiceLabels = SurveyResponse::choiceLabels();
        $columnStats = [];

        foreach (array_merge($teknisiColumns, $salesColumns) as $column) {
            $counts = (clone $responses)
                ->whereNotNull($column)
                ->select($column, DB::raw('COUNT(*) as total_count'))
                ->groupBy($column)
                ->pluck('total_count', $column);

            // Walk the defined choices (not just the ones present in the data)
            // so options with zero responses still show up in the detailed view.
            $breakdown = [];
            foreach ($choiceLabels[$column] as $value => $label) {
                $breakdown[$value] = [
                    'label' => $label,
                    'count' => (int) ($counts[$value] ?? 0),
                    'percent' => 0.0,
                ];
            }

            $total = array_sum(array_column($breakdown, 'count'));
            $top = null;
            $topCount = 0;

            foreach ($breakdown as $value => &$row) {
                $row['percent'] = $total > 0 ? round($row['count'] / $total * 100, 1) : 0.0;

                // Strict ">" keeps the first choice on a tie, so the result is stable.
                if ($row['count'] > $topCount) {
                    $topCount = $row['count'];
                    $top = $value;
                }
            }
            unset($row);

            $columnStats[$column] = [
                'total' => $total,
                'top' => $top,
                'breakdown' => $breakdown,
            ];
        }

        $responses = (clone $responses)->latest()->paginate(15)->withQueryString();

        return view('admin.responses.index', [
            'responses' => $responses,
            'filters' => $filters,
            'choiceLabels' => $choiceLabels,
            'totalRows' => $totalRows,
            'uniqueCustomers' => $uniqueCustomers,
            'meanKepuasan' => $meanKepuasan,
            'teknisiColumns' => $teknisiColumns,
            'salesColumns' => $salesColumns,
            'columnStats' => $columnStats,
        ]);
    }

    public function exportCsv(Request $request)
    {
        $fileName = 'survey_responses_' . now()->format('d-m-Y_His') . '.csv';
        return Excel::download(new SurveyResponsesExport($request, SurveyResponse::choiceLabels()), $fileName, \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportExcel(Request $request)
    {
        $fileName = 'survey_responses_' . now()->format('d-m-Y_His') . '.xlsx';
        return Excel::download(new SurveyResponsesExport($request, SurveyResponse::choiceLabels()), $fileName, \Maatwebsite\Excel\Excel::XLSX);
    }
}