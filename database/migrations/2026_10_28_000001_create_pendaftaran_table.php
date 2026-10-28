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
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lowongan_id')->nullable();
            $table->string('nama');
            $table->string('nim');
            $table->string('nilai_matkul')->nullable();
            $table->decimal('ipk', 3, 2)->nullable();
            $table->text('motivasi')->nullable();
            $table->string('portfolio')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('transkrip_path')->nullable();
            $table->string('status')->default('menunggu');
            $table->text('catatan_dosen')->nullable();
            $table->string('status_seleksi')->nullable()->comment('menunggu, disetujui, ditolak');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
