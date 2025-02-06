<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\StudyProgramController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\FacultyLecturerController;
use App\Http\Controllers\studyProgramLecturerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('users', UserController::class);
// });

Route::apiResource('users', UserController::class);
Route::apiResource('lecturers', LecturerController::class);
Route::apiResource('students', StudentController::class);
Route::apiResource('faculties', FacultyController::class);
Route::apiResource('study-programs', StudyProgramController::class);
Route::apiResource('semesters', SemesterController::class);
Route::apiResource('schedules', ScheduleController::class);
Route::apiResource('faculty-lecturers', FacultyLecturerController::class);
Route::apiResource('study-program-lecturers', StudyProgramLecturerController::class);

// route tambahan
// menampilkan schedules dengan info kelas
Route::get('/schedules/{schedule}/with-class-info', [ScheduleController::class, 'showWithClassInfo']);
// menampilkan schedules berdasarkan class_id
Route::get('/schedules/class/{classId}', [ScheduleController::class, 'getSchedulesByClassId']);
// menampilkan study program lecturer berdasarkan faculty id
Route::get('/study-program-lecturers/faculty/{facultyId}', [StudyProgramLecturerController::class, 'lecturersByFaculty']);