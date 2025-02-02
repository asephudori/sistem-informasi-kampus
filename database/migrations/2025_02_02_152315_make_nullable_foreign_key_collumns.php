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
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['advisory_class_id']);
            $table->dropForeign(['study_program_id']);

            $table->unsignedBigInteger('advisory_class_id')->nullable()->change();
            $table->unsignedBigInteger('study_program_id')->nullable()->change();

            $table->foreign('advisory_class_id')->references('id')->on('advisory_classes');
            $table->foreign('study_program_id')->references('id')->on('study_programs');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign(['permission_role_id']);

            $table->unsignedBigInteger('permission_role_id')->nullable()->change();

            $table->foreign('permission_role_id')->references('id')->on('permission_roles');
        });

        Schema::table('advisory_classes', function (Blueprint $table) {
            $table->dropForeign(['lecturer_id']);

            $table->unsignedBigInteger('lecturer_id')->nullable()->change();

            $table->foreign('lecturer_id')->references('id')->on('users');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['lecturer_id']);

            $table->unsignedBigInteger('lecturer_id')->nullable()->change();

            $table->foreign('lecturer_id')->references('id')->on('users');
        });

        Schema::table('study_programs', function (Blueprint $table) {
            $table->dropForeign(['faculty_id']);

            $table->unsignedBigInteger('faculty_id')->nullable()->change();

            $table->foreign('faculty_id')->references('id')->on('faculties');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['class_id']);
            $table->dropForeign(['grade_type_id']);

            $table->unsignedBigInteger('student_id')->nullable()->change();
            $table->unsignedBigInteger('class_id')->nullable()->change();
            $table->unsignedBigInteger('grade_type_id')->nullable()->change();

            $table->foreign('student_id')->references('id')->on('users');
            $table->foreign('class_id')->references('id')->on('classes');
            $table->foreign('grade_type_id')->references('id')->on('grade_types');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);

            $table->unsignedBigInteger('admin_id')->nullable()->change();

            $table->foreign('admin_id')->references('id')->on('users');
        });

        Schema::table('semester_fee', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['semester_id']);

            $table->unsignedBigInteger('student_id')->nullable()->change();
            $table->unsignedBigInteger('semester_id')->nullable()->change();

            $table->foreign('student_id')->references('id')->on('users');
            $table->foreign('semester_id')->references('id')->on('semesters');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropForeign(['transaction_category_id']);

            $table->unsignedBigInteger('admin_id')->nullable()->change();
            $table->unsignedBigInteger('transaction_category_id')->nullable()->change();

            $table->foreign('admin_id')->references('id')->on('users');
            $table->foreign('transaction_category_id')->references('id')->on('transaction_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['advisory_class_id']);
            $table->dropForeign(['study_program_id']);

            $table->unsignedBigInteger('advisory_class_id')->nullable(false)->change();
            $table->unsignedBigInteger('study_program_id')->nullable(false)->change();

            $table->foreign('advisory_class_id')->references('id')->on('advisory_classes');
            $table->foreign('study_program_id')->references('id')->on('study_programs');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign(['permission_role_id']);

            $table->unsignedBigInteger('permission_role_id')->nullable(false)->change();

            $table->foreign('permission_role_id')->references('id')->on('permission_roles');
        });

        Schema::table('advisory_classes', function (Blueprint $table) {
            $table->dropForeign(['lecturer_id']);

            $table->unsignedBigInteger('lecturer_id')->nullable(false)->change();

            $table->foreign('lecturer_id')->references('id')->on('users');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['lecturer_id']);

            $table->unsignedBigInteger('lecturer_id')->nullable(false)->change();

            $table->foreign('lecturer_id')->references('id')->on('users');
        });

        Schema::table('study_programs', function (Blueprint $table) {
            $table->dropForeign(['faculty_id']);

            $table->unsignedBigInteger('faculty_id')->nullable(false)->change();

            $table->foreign('faculty_id')->references('id')->on('faculties');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['class_id']);
            $table->dropForeign(['grade_type_id']);

            $table->unsignedBigInteger('student_id')->nullable(false)->change();
            $table->unsignedBigInteger('class_id')->nullable(false)->change();
            $table->unsignedBigInteger('grade_type_id')->nullable(false)->change();

            $table->foreign('student_id')->references('id')->on('users');
            $table->foreign('class_id')->references('id')->on('classes');
            $table->foreign('grade_type_id')->references('id')->on('grade_types');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);

            $table->unsignedBigInteger('admin_id')->nullable(false)->change();

            $table->foreign('admin_id')->references('id')->on('users');
        });

        Schema::table('semester_fee', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['semester_id']);

            $table->unsignedBigInteger('student_id')->nullable(false)->change();
            $table->unsignedBigInteger('semester_id')->nullable(false)->change();

            $table->foreign('student_id')->references('id')->on('users');
            $table->foreign('semester_id')->references('id')->on('semesters');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropForeign(['transaction_category_id']);

            $table->unsignedBigInteger('admin_id')->nullable(false)->change();
            $table->unsignedBigInteger('transaction_category_id')->nullable(false)->change();

            $table->foreign('admin_id')->references('id')->on('users');
            $table->foreign('transaction_category_id')->references('id')->on('transaction_categories');
        });
    }
};
