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
        Schema::table('advisory_classes', function (Blueprint $table) {
            $table->dropForeign('advisory_classes_user_id_foreign');

            $table->foreign('lecturer_id', 'advisory_classes_lecturer_id_foreign')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('class_members', function (Blueprint $table) {
            $table->dropForeign('class_members_user_id_foreign');

            $table->foreign('student_id', 'class_members_student_id_foreign')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign('classes_user_id_foreign');

            $table->foreign('lecturer_id', 'classes_lecturer_id_foreign')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('study_program_lecturers', function (Blueprint $table) {
            $table->dropForeign('study_program_lecturers_user_id_foreign');

            $table->foreign('lecturer_id', 'study_program_lecturers_lecturer_id_foreign')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('faculty_lecturers', function (Blueprint $table) {
            $table->dropForeign('faculty_lecturers_user_id_foreign');

            $table->foreign('lecturer_id', 'faculty_lecturers_lecturer_id_foreign')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign('grades_user_id_foreign');

            $table->foreign('student_id', 'grades_student_id_foreign')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign('news_user_id_foreign');

            $table->foreign('admin_id', 'news_admin_id_foreign')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('semester_fee', function (Blueprint $table) {
            $table->dropForeign('semester_fee_user_id_foreign');

            $table->foreign('student_id', 'semester_fee_student_id_foreign')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_user_id_foreign');

            $table->foreign('admin_id', 'transactions_admin_id_foreign')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advisory_classes', function (Blueprint $table) {
            $table->dropForeign('advisory_classes_lecturer_id_foreign');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('class_members', function (Blueprint $table) {
            $table->dropForeign('class_members_student_id_foreign');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign('classes_lecturer_id_foreign');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('study_program_lecturers', function (Blueprint $table) {
            $table->dropForeign('study_program_lecturers_lecturer_id_foreign');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('faculty_lecturers', function (Blueprint $table) {
            $table->dropForeign('faculty_lecturers_lecturer_id_foreign');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign('grades_student_id_foreign');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign('news_admin_id_foreign');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('semester_fee', function (Blueprint $table) {
            $table->dropForeign('semester_fee_student_id_foreign');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_admin_id_foreign');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
