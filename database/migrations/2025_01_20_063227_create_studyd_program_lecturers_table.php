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
        Schema::table('study_program_lecturers', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('study_program_id')->constrained('study_programs')->onDelete('cascade');
            $table->string('lecturer_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('study_program_lecturers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['study_program_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('study_program_id');
            $table->dropColumn('lecturer_position');
        });
    }
};
