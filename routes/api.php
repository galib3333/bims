<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IncidentController;

Route::post('/incidents', [IncidentController::class, 'store']);
Route::get('/incidents', [IncidentController::class, 'index']);
Route::get('/incidents/map', [IncidentController::class, 'map']);
