<?php

use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');
Route::get('/survey/terima-kasih', [SurveyController::class, 'thanks'])->name('survey.thanks');
Route::get('/survey/{customerId?}', [SurveyController::class, 'create'])->name('survey.create');