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
        Schema::create('advisory_classes', function (Blueprint $table) {
            $table->id('advisory_class_id');
            $table->foreignId('user_id')->constrained(table: 'users')->onDelete('cascade');
            $table->year('class_year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advisory_class');
    }
};
