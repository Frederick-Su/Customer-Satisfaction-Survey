<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
                    $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%")
                        ->orWhere('saran', 'like', "%{$search}%");
                });
            })
            ->when($filters['rating'] ?? null, fn ($query, int $rating) => $query->where('kepuasan_keseluruhan', $rating))
            ->when($filters['from'] ?? null, fn ($query, string $from) => $query->whereDate('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($query, string $to) => $query->whereDate('created_at', '<=', $to))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.responses.index', [
            'responses' => $responses,
            'filters' => $filters,
            'choiceLabels' => SurveyResponse::choiceLabels(),
        ]);
    }
}