<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdvisoryClassController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GradeFormatController;
use App\Http\Controllers\GradeTypeController;
use App\Http\Controllers\LearningClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PermissionRoleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('users', UserController::class);
// });

Route::apiResource('users', UserController::class);
Route::apiResource('lecturers', LecturerController::class);
Route::apiResource('students', StudentController::class);
Route::apiResource('admins', AdminController::class);
Route::apiResource('permission-roles' ,PermissionRoleController::class);
Route::apiResource('permissions', PermissionController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('learning-classes', LearningClassController::class);
Route::apiResource('advisory-classes', AdvisoryClassController::class);
Route::apiResource('grades', GradeController::class);
Route::apiResource('grade-types', GradeTypeController::class);
Route::apiResource('grade-formats', GradeFormatController::class);
