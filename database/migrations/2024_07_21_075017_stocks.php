<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('kode');
            $table->integer('stock')->default(0);
            $table->decimal('beli', 19, 2)->default(0);
            $table->decimal('jual', 19, 2)->default(0);
            $table->foreignId('suppliers_id')->constrained()->onDelete('cascade');
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('stocks');
    }
};
