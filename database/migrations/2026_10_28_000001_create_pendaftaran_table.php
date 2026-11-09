<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            // Foreign keys
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // Asumsi lowongan mengambil dari tabel pengumuman
            $table->foreignId('lowongan_id')->constrained('pengumuman')->cascadeOnDelete(); 
            
            // Data pendaftaran
            $table->string('nilai_matkul')->nullable();
            $table->text('motivasi')->nullable();
            $table->string('file_ksm_path')->nullable();
            
            // Status seleksi
            $table->string('status')->default('menunggu'); // Publik (mahasiswa)
            $table->string('status_seleksi')->default('menunggu'); // Internal (dosen)
            $table->text('catatan_dosen')->nullable();
            $table->boolean('is_published')->default(false);
            
            $table->timestamps();

            // Constraint unik: 1 Mahasiswa (user_id) hanya bisa mendaftar 1x di lowongan yang sama
            $table->unique(['user_id', 'lowongan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};