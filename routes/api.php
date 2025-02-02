<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\StudyProgramController;

Route::apiResource('faculties', FacultyController::class);
Route::apiResource('study-programs', StudyProgramController::class);
