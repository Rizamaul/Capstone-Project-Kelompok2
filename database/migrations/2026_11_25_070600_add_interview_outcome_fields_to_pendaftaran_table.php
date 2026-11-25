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
            $table->string('metode_wawancara', 20)->nullable()->after('jadwal_wawancara');
            $table->dateTime('waktu_wawancara')->nullable()->after('metode_wawancara');
            $table->string('hasil_wawancara', 20)->nullable()->after('catatan_wawancara');
            $table->text('catatan_hasil_wawancara')->nullable()->after('hasil_wawancara');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn([
                'metode_wawancara',
                'waktu_wawancara',
                'hasil_wawancara',
                'catatan_hasil_wawancara',
            ]);
        });
    }
};
