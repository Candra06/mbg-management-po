<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_invoice')->unique();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->foreignId('inspection_id')->constrained('inspections')->onDelete('cascade');
            $table->date('tanggal_invoice');
            $table->decimal('subtotal_po', 12, 2);
            $table->decimal('potongan_ketidaksesuaian', 12, 2)->default(0);
            $table->decimal('total_tagihan', 12, 2);
            $table->enum('status', ['draft', 'diterbitkan', 'lunas'])->default('diterbitkan');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
