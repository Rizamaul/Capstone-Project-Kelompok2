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
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('practicum_id')->constrained()->onDelete('cascade');
            
            // Evaluasi Dosen
            $table->boolean('is_marked_for_interview')->default(false);
            $table->text('evaluation_notes')->nullable();
            
            // Logika Kerahasiaan Status
            $table->enum('internal_status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->enum('public_status', ['terdaftar', 'diterima', 'ditolak'])->default('terdaftar');
            $table->boolean('is_published')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
