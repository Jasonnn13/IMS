<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rincianpembelian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stocks_id')->constrained()->onDelete('cascade');
            $table->foreignId('pembelian_id')->constrained('pembelian')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 19, 2)->default(0);
            $table->decimal('total', 19, 2)->default(0);
            $table->timestamps(); // Adds created_at and updated_at columns 
        });
    }

    public function down()
    {
        Schema::dropIfExists('rincianpembelian');
    }
};
