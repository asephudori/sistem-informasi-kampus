<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdvisoryClassController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GradeFormatController;
use App\Http\Controllers\GradeTypeController;
use App\Http\Controllers\LearningClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PermissionRoleController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {  // Route contoh bawaan laravel
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::middleware('check_permission')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('admins', AdminController::class);
        Route::apiResource('permission-roles' ,PermissionRoleController::class);
        Route::apiResource('permissions', PermissionController::class);

        Route::apiResource('grade-types', GradeTypeController::class);  // lecturer
        Route::apiResource('grade-formats', GradeFormatController::class);  // lecturer
        Route::apiResource('classrooms', ClassroomController::class);  // lecturer

        Route::apiResource('lecturers', LecturerController::class);  // student, lecturer
        Route::apiResource('students', StudentController::class);  // student, lecturer
        Route::apiResource('courses', CourseController::class);  // student, lecturer
        Route::apiResource('learning-classes', LearningClassController::class);  // student, lecturer
        Route::apiResource('advisory-classes', AdvisoryClassController::class);  // student, lecturer
        Route::apiResource('grades', GradeController::class);  // student, lecturer
    });
});
