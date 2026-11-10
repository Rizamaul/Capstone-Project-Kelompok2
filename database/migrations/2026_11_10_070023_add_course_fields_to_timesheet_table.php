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
        Schema::table('timesheet', function (Blueprint $table) {
            $table->string('mata_kuliah', 300)->nullable();
            $table->string('aktivitas', 120)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timesheet', function (Blueprint $table) {
            $table->dropColumn(['mata_kuliah', 'aktivitas']);
        });
    }
};
