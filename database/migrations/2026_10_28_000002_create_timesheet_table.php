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
        Schema::create('timesheet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asisten_id');
            $table->date('tanggal');
            $table->decimal('jumlah_jam', 4, 2)->default(0);
            $table->text('uraian_pekerjaan');
            $table->string('status_approval')->default('pending');
            $table->text('catatan_revisi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timesheet');
    }
};
