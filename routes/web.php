<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacultyController;

Route::get('/', function () {
    return view('welcome');
});

Route::apiResource('faculties', FacultyController::class);