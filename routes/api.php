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
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\StudyProgramController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\FacultyLecturerController;
use App\Http\Controllers\StudyProgramLecturerController;
use App\Http\Controllers\ClassMemberController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SemesterFeeController;
use App\Http\Controllers\TransactionCategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UniversityInformationController;
use App\Http\Controllers\PaymentReminderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserInfoController;
use App\Http\Controllers\ActivityLogController;

Route::apiResource('faculties', FacultyController::class);
Route::apiResource('study-programs', StudyProgramController::class);
Route::apiResource('semesters', SemesterController::class);
Route::apiResource('schedules', ScheduleController::class);
Route::apiResource('faculty-lecturers', FacultyLecturerController::class);
Route::apiResource('study-program-lecturers', StudyProgramLecturerController::class);
Route::apiResource('class-members', ClassMemberController::class);
Route::apiResource('news', NewsController::class);
Route::apiResource('university-informations', UniversityInformationController::class);
Route::apiResource('semester-fees', SemesterFeeController::class);
Route::apiResource('transaction-categories', TransactionCategoryController::class);
Route::apiResource('transactions', TransactionController::class);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {  // Route contoh bawaan laravel
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user-info', [UserInfoController::class, 'index']);
    Route::put('/user-info', [UserInfoController::class, 'update']);

    Route::middleware(['check_permission', 'update.last_used'])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('admins', AdminController::class);
        Route::apiResource('permission-roles', PermissionRoleController::class);
        Route::apiResource('permissions', PermissionController::class);

        Route::apiResource('grade-types', GradeTypeController::class);
        Route::apiResource('grade-formats', GradeFormatController::class);
        Route::apiResource('classrooms', ClassroomController::class);

        Route::apiResource('lecturers', LecturerController::class);
        Route::apiResource('students', StudentController::class);
        Route::apiResource('courses', CourseController::class);
        Route::apiResource('learning-classes', LearningClassController::class);
        Route::apiResource('advisory-classes', AdvisoryClassController::class);
        Route::apiResource('grades', GradeController::class);
    });

    // Route tambahan
    Route::get('/schedules/{schedule}/with-class-info', [ScheduleController::class, 'showWithClassInfo']);
    Route::get('/schedules/class/{classId}', [ScheduleController::class, 'getSchedulesByClassId']);
    Route::get('/study-program-lecturers/faculty/{facultyId}', [StudyProgramLecturerController::class, 'lecturersByFaculty']);
    Route::get('/class-members/class/{classId}', [ClassMemberController::class, 'getClassMembersByClass']);
    Route::get('/transactions/category/{transaction_category_id}', [TransactionController::class, 'transactionsByCategory']);
    Route::get('/transactions/report', [TransactionController::class, 'transactionsByCategoryReport']);
    Route::post('/transactions/financial-report', [TransactionController::class, 'financialReport']);

    // Payment reminder routes
    Route::post('/semester-fees/send-reminder', [PaymentReminderController::class, 'sendReminder']);
    Route::post('/semester-fees/send-reminder-manual', [PaymentReminderController::class, 'sendReminderManual']);
    Route::post('/semester-fees/upload-payment-proof/{semesterFeeId}', [PaymentReminderController::class, 'uploadPaymentProof']);
    Route::post('/semester-fees/send-success-notification', [PaymentReminderController::class, 'sendSuccessNotification']);
    Route::post('/semester-fees/set-due-date', [PaymentReminderController::class, 'setDueDate']);
    Route::post('/semester-fees/edit-due-date', [PaymentReminderController::class, 'editDueDate']);
    Route::get('/semester-fees/payment-proof', [PaymentReminderController::class, 'getPaymentProofs']);
    Route::get('/semester-fees/admins', [PaymentReminderController::class, 'getAdmins']);

Route::get('/test', function () {
    return response()->json(['message' => 'success'], 200);
});


    // Log Activity
    Route::get('/activity-logs', [ActivityLogController::class, 'index']);
});