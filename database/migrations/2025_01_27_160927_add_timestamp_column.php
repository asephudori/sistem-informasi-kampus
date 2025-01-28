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
        Schema::table('permission_roles', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('university_informations', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('grade_format', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('permissions', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('transaction_categories', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('grade_types', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('news', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('transactions', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('grades', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('semester_fee', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('students', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('lecturers', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('admins', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('permission_groups', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('faculty_lecturers', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('study_program_lecturers', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permission_roles', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('university_informations', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('grade_format', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('transaction_categories', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('grade_types', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('news', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('grades', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('semester_fee', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('students', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('lecturers', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('admins', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('permission_groups', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('faculty_lecturers', function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table('study_program_lecturers', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};
