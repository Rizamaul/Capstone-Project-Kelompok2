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
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->boolean('berkas_diverifikasi')->default(false)->after('transkrip_path');
            $table->timestamp('waktu_verifikasi_berkas')->nullable()->after('berkas_diverifikasi');
            $table->string('jadwal_wawancara')->nullable()->after('waktu_verifikasi_berkas');
            $table->string('lokasi_wawancara')->nullable()->after('jadwal_wawancara');
            $table->text('catatan_wawancara')->nullable()->after('lokasi_wawancara');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn([
                'berkas_diverifikasi',
                'waktu_verifikasi_berkas',
                'jadwal_wawancara',
                'lokasi_wawancara',
                'catatan_wawancara',
            ]);
        });
    }
};
