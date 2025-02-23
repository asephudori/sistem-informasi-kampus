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
use App\Http\Controllers\PaymentReminderController;
use App\Http\Controllers\PaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('users', UserController::class);
// });

// route tambahan
// menampilkan schedules dengan info kelas
Route::get('/schedules/{schedule}/with_class_info', [ScheduleController::class, 'showWithClassInfo']);
// menampilkan schedules berdasarkan class_id
Route::get('/schedules/class/{classId}', [ScheduleController::class, 'getSchedulesByClassId']);
// menampilkan study program lecturer berdasarkan faculty id
Route::get('/study_program_lecturers/faculty/{facultyId}', [StudyProgramLecturerController::class, 'lecturersByFaculty']);
// menampilkan class member by class id
Route::get('/class_members/class/{classId}', [ClassMemberController::class, 'getClassMembersByClass']);
// menampilkan transaksi per kategori
Route::get('/transactions/category/{transaction_category_id}', [TransactionController::class, 'transactionsByCategory']);
// menampilkan transaksi report lengkap dengan category id dan rentang waktu
Route::get('/transactions/report', [TransactionController::class, 'transactionsByCategoryReport']);
// menampilkan laporan keuangan
Route::post('/transactions/financial-report', [TransactionController::class, 'financialReport']);

// Payment reminder routes
Route::post('/semester_fees/send_reminder', [PaymentReminderController::class, 'sendReminder']);
Route::post('/semester_fees/send_reminder_manual', [PaymentReminderController::class, 'sendReminderManual']);
Route::post('/semester_fees/upload_payment_proof/{semesterFeeId}', [PaymentReminderController::class, 'uploadPaymentProof']);
Route::post('/semester_fees/send_success_notification', [PaymentReminderController::class, 'sendSuccessNotification']);
Route::post('/semester_fees/set_due_date', [PaymentReminderController::class, 'setDueDate']);
Route::post('/semester_fees/edit_due_date', [PaymentReminderController::class, 'editDueDate']);
Route::get('/semester_fees/payment_proof', [PaymentReminderController::class, 'getPaymentProofs']);
Route::get('/semester_fees/admins', [PaymentReminderController::class, 'getAdmins']);

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
Route::apiResource('faculties', FacultyController::class);
Route::apiResource('study_programs', StudyProgramController::class);
Route::apiResource('semesters', SemesterController::class);
Route::apiResource('schedules', ScheduleController::class);
Route::apiResource('faculty_lecturers', FacultyLecturerController::class);
Route::apiResource('study_program_lecturers', StudyProgramLecturerController::class);
Route::apiResource('class_members', ClassMemberController::class);
Route::apiResource('news', NewsController::class);
Route::apiResource('university_informations', UniversityInformationController::class);
Route::apiResource('semester_fees', SemesterFeeController::class);
Route::apiResource('transaction_categories', TransactionCategoryController::class);
Route::apiResource('transactions', TransactionController::class);