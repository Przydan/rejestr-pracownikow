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
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_id')->unique()->after('id');
            $table->string('department')->nullable()->after('employee_id');
            $table->string('phone')->nullable()->after('department');
            $table->text('address')->nullable()->after('phone');
            $table->text('notes')->nullable()->after('address');
            $table->string('photo_path')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employee_id', 'department', 'phone', 'address', 'notes', 'photo_path']);
        });
    }
};
