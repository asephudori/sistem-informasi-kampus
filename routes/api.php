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
use App\Http\Controllers\ClassMemberController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SemesterFeeController;
use App\Http\Controllers\TransactionCategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UniversityInformationController;

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
Route::apiResource('class-members', ClassMemberController::class);
Route::apiResource('news',NewsController::class);
Route::apiResource('university-information',UniversityInformationController::class);
Route::apiResource('semester-fee',SemesterFeeController::class);
Route::apiResource('transaction-category',TransactionCategoryController::class);
Route::apiResource('transaction',TransactionController::class);

// route tambahan
// menampilkan schedules dengan info kelas
Route::get('/schedules/{schedule}/with-class-info', [ScheduleController::class, 'showWithClassInfo']);
// menampilkan schedules berdasarkan class_id
Route::get('/schedules/class/{classId}', [ScheduleController::class, 'getSchedulesByClassId']);
// menampilkan study program lecturer berdasarkan faculty id
Route::get('/study-program-lecturers/faculty/{facultyId}', [StudyProgramLecturerController::class, 'lecturersByFaculty']);
// menampilkan class member by class id
Route::get('/class-members/class/{classId}', [ClassMemberController::class, 'getClassMembersByClass']);
// mencari class member dengan beberapa parameter
Route::get('/class-members/search', [ClassMemberController::class, 'search']);
// mencari news dengan beberapa parameter
Route::get('/news/search', [NewsController::class, 'search']);