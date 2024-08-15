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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->decimal('pemasukan', 19, 2)->default(0);
            $table->decimal('pengeluaran', 19, 2)->default(0);
            $table->decimal('profit', 19, 2)->default(0);
            $table->year('tahun'); 
            $table->tinyInteger('bulan'); 
            $table->timestamps();   
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
