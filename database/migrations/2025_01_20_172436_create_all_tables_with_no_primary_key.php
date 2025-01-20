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
        // Tabel students
        Schema::create('students', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('advisory_class_id')->constrained('study_programs')->onDelete('cascade');
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

        // Tabel lecturers
        Schema::create('lecturers', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nidn', 20)->unique();
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('phone', 15)->nullable();
            $table->string('address', 100);
            $table->date('entry_date');
            $table->string('birthplace', 50);
            $table->date('birthdate');
            $table->enum('gender', ['male', 'female']);
        });

        // Tabel admins
        Schema::create('admins', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('permission_role_id')->constrained('permission_roles')->onDelete('cascade');
            $table->string('name');
            $table->enum('role', ['super admin', 'admin'])->default('admin');
        });

        // Tabel permission_groups
        Schema::create('permission_groups', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
            $table->foreignId('permission_role_id')->constrained('permission_roles')->onDelete('cascade');
        });

        // Tabel faculty_lecturers
        Schema::create('faculty_lecturers', function (Blueprint $table) {
            $table->foreignId(('user_id'))->constrained('users')->onDelete('cascade');
            $table->foreignId('faculty_id')->constrained('faculties')->onDelete('cascade');
            $table->string('lecturer_position');
        });

        // Tabel study_program_lecturers
        Schema::create('study_program_lecturers', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('study_program_id')->constrained('study_programs')->onDelete('cascade');
            $table->string('lecturer_position');
        });

        // Tabel class_members
        Schema::create('class_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->char('semester_grades', 5);
            $table->timestamps();
        });

        // Tabel schedules
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->enum('day', ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
        Schema::dropIfExists('lecturers');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('permission_groups');
        Schema::dropIfExists('faculty_lecturers');
        Schema::dropIfExists('study_program_lecturers');
        Schema::dropIfExists('class_members');
        Schema::dropIfExists('schedules');
    }
};
