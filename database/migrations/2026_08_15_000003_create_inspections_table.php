<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->dateTime('tanggal_cek');
            $table->string('nama_pemeriksa');
            $table->integer('total_item')->default(0);
            $table->integer('total_sesuai')->default(0);
            $table->integer('total_tidak_sesuai')->default(0);
            $table->string('status_keseluruhan')->default('Lolos QC'); // Lolos QC, Catatan QC, Ditolak
            $table->text('catatan_umum')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
