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
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->string('kode', 20)->nullable();
            $table->unsignedTinyInteger('sks')->default(3);
            $table->string('jadwal', 100)->nullable();
            $table->string('kelas', 50)->nullable();
            $table->string('semester', 60)->nullable();
            $table->string('syarat_nilai', 10)->nullable();
            $table->decimal('syarat_ipk', 3, 2)->nullable();
            $table->text('deskripsi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->dropColumn([
                'kode',
                'sks',
                'jadwal',
                'kelas',
                'semester',
                'syarat_nilai',
                'syarat_ipk',
                'deskripsi',
            ]);
        });
    }
};
