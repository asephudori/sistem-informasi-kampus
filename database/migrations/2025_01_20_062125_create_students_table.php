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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('advisory_class_id')->constrained()->onDelete('cascade');
            $table->foreignId('study_program_id')->constrained()->onDelete('cascade');
            $table->string('nim', 20)->unique();
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('phone', 15)->nullable();
            $table->string('birthplace', 50);
            $table->date('birthdate');
            $table->string('home_address', 100);
            $table->string('current_address', 100);
            $table->string('home_city_district', 50);
            $table->string('home_postal_code', 10);
            $table->enum('gender', ['male', 'female']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['advisory_class_id']);
            $table->dropForeign(['study_program_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('advisory_class_id');
            $table->dropColumn('study_program_id');
            $table->dropColumn('nim');
            $table->dropColumn('name');
            $table->dropColumn('email');
            $table->dropColumn('phone');
            $table->dropColumn('birthplace');
            $table->dropColumn('birthdate');
            $table->dropColumn('home_address');
            $table->dropColumn('current_address');
            $table->dropColumn('home_city_district');
            $table->dropColumn('home_postal_code');
        });
    }
};
