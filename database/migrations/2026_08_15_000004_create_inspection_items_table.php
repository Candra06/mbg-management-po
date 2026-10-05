<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained('inspections')->onDelete('cascade');
            $table->foreignId('po_item_id')->constrained('po_items')->onDelete('cascade');
            $table->decimal('qty_terima', 10, 2);
            $table->enum('status_jumlah', ['sesuai', 'tidak_sesuai'])->default('sesuai');
            $table->string('warna')->default('Segar/Normal'); // Segar/Normal, Pucat, Perubahan Warna, Memar/Bintik
            $table->string('aroma')->default('Segar/Normal'); // Segar/Normal, Berbau, Apek, Busuk
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_items');
    }
};
