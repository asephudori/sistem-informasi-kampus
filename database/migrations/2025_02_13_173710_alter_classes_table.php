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
            $table->dropForeign(['study_program_id']);
            $table->dropForeign(['classroom_id']);
            $table->dropForeign(['course_id']);
            $table->dropForeign(['semester_id']);

            $table->unsignedBigInteger('study_program_id')->nullable()->change();
            $table->unsignedBigInteger('classroom_id')->nullable()->change();
            $table->unsignedBigInteger('course_id')->nullable()->change();
            $table->unsignedBigInteger('semester_id')->nullable()->change();

            $table->foreign('study_program_id')->references('id')->on('study_programs');
            $table->foreign('classroom_id')->references('id')->on('classrooms');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('semester_id')->references('id')->on('semesters');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['study_program_id']);
            $table->dropForeign(['classroom_id']);
            $table->dropForeign(['course_id']);
            $table->dropForeign(['semester_id']);

            $table->unsignedBigInteger('study_program_id')->nullable(false)->change();
            $table->unsignedBigInteger('classroom_id')->nullable(false)->change();
            $table->unsignedBigInteger('course_id')->nullable(false)->change();
            $table->unsignedBigInteger('semester_id')->nullable(false)->change();

            $table->foreign('study_program_id')->references('id')->on('study_programs');
            $table->foreign('classroom_id')->references('id')->on('classrooms');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('semester_id')->references('id')->on('semesters');
        });
    }
};
