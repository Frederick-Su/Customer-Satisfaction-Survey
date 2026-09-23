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

        $modes = [];
        foreach (array_merge($teknisiColumns, $salesColumns) as $column) {
            $topValue = (clone $responses)
                ->select($column, DB::raw('COUNT(*) as total_count'))
                ->whereNotNull($column)
                ->groupBy($column)
                ->orderByDesc('total_count')
                ->first();

            $modes[$column] = $topValue ? $topValue->$column : null;
        }

        $responses = (clone $responses)->latest()->paginate(15)->withQueryString();

        return view('admin.responses.index', [
            'responses' => $responses,
            'filters' => $filters,
            'choiceLabels' => SurveyResponse::choiceLabels(),
            'totalRows' => $totalRows,
            'uniqueCustomers' => $uniqueCustomers,
            'meanKepuasan' => $meanKepuasan,
            'teknisiColumns' => $teknisiColumns,
            'salesColumns' => $salesColumns,
            'modes' => $modes,
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