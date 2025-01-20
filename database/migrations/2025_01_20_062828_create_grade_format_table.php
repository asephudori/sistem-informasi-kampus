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
        Schema::table('grade_format', function (Blueprint $table) {
            $table->id();
            $table->string('min_grade');
            $table->string('max_grade');
            $table->string('alphabetical_grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grade_format', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropColumn('min_grade');
            $table->dropColumn('max_grade');
            $table->dropColumn('alphabetical_grade');
        });
    }
};
