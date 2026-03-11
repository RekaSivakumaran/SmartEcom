<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
        $table->enum('type', ['in', 'out']);
        $table->integer('quantity');
        $table->integer('quantity_before');
        $table->integer('quantity_after');
        $table->string('reason')->nullable();  
        $table->string('note')->nullable();
        $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
