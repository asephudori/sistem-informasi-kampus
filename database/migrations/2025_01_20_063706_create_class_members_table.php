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
        Schema::create('class_members', function (Blueprint $table) {
            $table->id('class_member_id');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('students')->onDelete('cascade');
            $table->char('semester_grades', 5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_members');
    }
};
