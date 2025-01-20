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
        Schema::table('admins', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_role_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('role', ['super admin', 'admin'])->default('admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['permission_role_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('permission_role_id');
            $table->dropColumn('name');
            $table->dropColumn('role');
        });
    }
};
