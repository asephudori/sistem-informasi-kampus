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
        Schema::table('faculty_lecturers', function (Blueprint $table) {
            $table->foreignId(('user_id'))->constrained()->onDelete('cascade');
            $table->foreignId('faculty_id')->constrained()->onDelete('cascade');
            $table->string('lecturer_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faculty_lecturers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['faculty_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('faculty_id');
            $table->dropColumn('lecturer_position');
        });
    }
};
