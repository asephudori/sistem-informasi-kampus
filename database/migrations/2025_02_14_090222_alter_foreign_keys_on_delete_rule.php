<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
            $table->dropForeign(['study_program_id']);
            $table->dropForeign(['course_id']);
            $table->dropForeign(['semester_id']);
            $table->dropForeign(['lecturer_id']);

            $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('set null');
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('set null');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
            $table->foreign('lecturer_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('news', function (Blueprint $table) {

        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropForeign(['transaction_category_id']);

            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('transaction_category_id')->references('id')->on('transaction_categories')->onDelete('set null');
            $table->dropForeign(['admin_id']);
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['advisory_class_id']);
            $table->dropForeign(['study_program_id']);

            $table->foreign('advisory_class_id')->references('id')->on('advisory_classes')->onDelete('set null');
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('set null');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['grade_type_id']);
            $table->dropForeign(['class_id']);
            $table->dropForeign(['student_id']);

            $table->foreign('grade_type_id')->references('id')->on('grade_types')->onDelete('set null');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign(['permission_role_id']);
            $table->foreign('permission_role_id')->references('id')->on('permission_roles')->onDelete('set null');
        });

        Schema::table('advisory_classes', function (Blueprint $table) {
            $table->dropForeign(['lecturer_id']);
            $table->foreign('lecturer_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('study_programs', function (Blueprint $table) {
            $table->dropForeign(['faculty_id']);
            $table->foreign('faculty_id')->references('id')->on('faculties')->onDelete('cascade');
        });

        Schema::table('semester_fee', function (Blueprint $table) {
            $table->dropForeign(['semester_id']);
            $table->dropForeign(['student_id']);

            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
            $table->dropForeign(['study_program_id']);
            $table->dropForeign(['course_id']);
            $table->dropForeign(['semester_id']);
            $table->dropForeign(['lecturer_id']);

            $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('no action');
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('no action');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('no action');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('no action');
            $table->foreign('lecturer_id')->references('id')->on('users')->onDelete('no action');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('no action');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropForeign(['transaction_category_id']);

            $table->foreign('admin_id')->references('id')->on('users')->onDelete('no action');
            $table->foreign('transaction_category_id')->references('id')->on('users')->onDelete('no action');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['advisory_class_id']);
            $table->dropForeign(['study_program_id']);

            $table->foreign('advisory_class_id')->references('id')->on('advisory_classes')->onDelete('no action');
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('no action');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['grade_type_id']);
            $table->dropForeign(['class_id']);
            $table->dropForeign(['student_id']);

            $table->foreign('grade_type_id')->references('id')->on('grade_types')->onDelete('no action');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('no action');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('no action');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign(['permission_role_id']);
            $table->foreign('permission_role_id')->references('id')->on('permission_roles')->onDelete('no action');
        });

        Schema::table('advisory_classes', function (Blueprint $table) {
            $table->dropForeign(['lecturer_id']);
            $table->foreign('lecturer_id')->references('id')->on('users')->onDelete('no action');
        });

        Schema::table('study_programs', function (Blueprint $table) {
            $table->dropForeign(['faculty_id']);
            $table->foreign('faculty_id')->references('id')->on('faculties')->onDelete('no action');
        });

        Schema::table('semester_fee', function (Blueprint $table) {
            $table->dropForeign(['semester_id']);
            $table->dropForeign(['student_id']);

            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('no action');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('no action');
        });
    }
};
