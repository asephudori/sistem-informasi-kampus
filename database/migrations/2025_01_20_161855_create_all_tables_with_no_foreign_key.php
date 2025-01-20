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
        // Tabel permission_roles
        Schema::create('permission_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
        });

        // Tabel university_informations
        Schema::create('university_informations', function (Blueprint $table) {
            $table->string('name');
            $table->string('address');
            $table->string('regency');
            $table->string('postal_code');
            $table->string('logo');
        });

        // Tabel grade_format
        Schema::create('grade_format', function (Blueprint $table) {
            $table->id();
            $table->string('min_grade');
            $table->string('max_grade');
            $table->string('alphabetical_grade');
        });

        // Tabel permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
        });

        // Tabel transaction_categories
        Schema::create('transaction_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->enum('type', ['operating_revenue', 'non_operating_revenue', 'operating_expense', 'non_operating_expense']);
        });

        // Tabel grade_types
        Schema::create('grade_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('percentage');
        });

        // Tabel faculties
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->char('name', 50);
            $table->timestamps();
        });

        // Tabel semesters
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->char('name', 10);
            $table->date('start_periode');
            $table->date('end_periode');
            $table->timestamps();
        });

        // Tabel courses
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->char('name', 30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_roles');
        Schema::dropIfExists('university_informations');
        Schema::dropIfExists('grade_format');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('transaction_categories');
        Schema::dropIfExists('grade_types');
        Schema::dropIfExists('faculties');
        Schema::dropIfExists('semesters');
        Schema::dropIfExists('courses');
    }
};
