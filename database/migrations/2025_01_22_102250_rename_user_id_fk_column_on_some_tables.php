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
        Schema::table('grades', function (Blueprint $table) {
            $table->renameColumn('user_id', 'student_id');
        });
        
        Schema::table('semester_fee', function (Blueprint $table) {
            $table->renameColumn('user_id', 'student_id');
        });
        
        Schema::table('class_members', function (Blueprint $table) {
            $table->renameColumn('user_id', 'student_id');
        });
        
        Schema::table('classes', function (Blueprint $table) {
            $table->renameColumn('user_id', 'lecturer_id');
        });
        
        Schema::table('advisory_classes', function (Blueprint $table) {
            $table->renameColumn('user_id', 'lecturer_id');
        });
        
        Schema::table('faculty_lecturers', function (Blueprint $table) {
            $table->renameColumn('user_id', 'lecturer_id');
        });
        
        Schema::table('study_program_lecturers', function (Blueprint $table) {
            $table->renameColumn('user_id', 'lecturer_id');
        });
        
        Schema::table('news', function (Blueprint $table) {
            $table->renameColumn('user_id', 'admin_id');
        });
        
        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('user_id', 'admin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->renameColumn('student_id', 'user_id');
        });
        
        Schema::table('semester_fee', function (Blueprint $table) {
            $table->renameColumn('student_id', 'user_id');
        });

        Schema::table('class_members', function (Blueprint $table) {
            $table->renameColumn('student_id', 'user_id');
        });
        
        Schema::table('classes', function (Blueprint $table) {
            $table->renameColumn('lecturer_id', 'user_id');
        });
        
        Schema::table('advisory_classes', function (Blueprint $table) {
            $table->renameColumn('lecturer_id', 'user_id');
        });
        
        Schema::table('faculty_lecturers', function (Blueprint $table) {
            $table->renameColumn('lecturer_id', 'user_id');
        });
        
        Schema::table('study_program_lecturers', function (Blueprint $table) {
            $table->renameColumn('lecturer_id', 'user_id');
        });
        
        Schema::table('news', function (Blueprint $table) {
            $table->renameColumn('admin_id', 'user_id');
        });
        
        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('admin_id', 'user_id');
        });
    }
};
