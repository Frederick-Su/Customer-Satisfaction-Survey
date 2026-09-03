<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyResponseRequest;
use App\Models\SurveyResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SurveyController extends Controller
{
    /**
     * Show the survey form.
     */
    public function create(): View
    {
        return view('survey.index');
    }

    /**
     * Validate and store a submitted survey response.
     */
    public function store(StoreSurveyResponseRequest $request): RedirectResponse
    {
        SurveyResponse::create($request->validated());

        return redirect()
            ->route('survey.thanks');
    }

    /**
     * Show the thank-you confirmation page.
     */
    public function thanks(): View
    {
        return view('survey.thanks');
    }
}
