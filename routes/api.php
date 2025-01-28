<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PredictiveAnalyticsController;

//customer churn predictive analytics endpoint
Route::post('/predict-churn', [PredictiveAnalyticsController::class, 'predictChurn']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
