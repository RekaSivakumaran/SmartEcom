<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            
            $table->unsignedBigInteger('main_category_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->unsignedBigInteger('brand_id');
            $table->integer('quantity');

            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);

          
            $table->decimal('discount_rate', 5, 2)->default(0.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
           
            $table->enum('discount_type', ['none', 'rate', 'amount'])->default('none');

                  

            $table->timestamps();

        
            $table->foreign('main_category_id')->references('id')->on('main_categories')->onDelete('cascade');
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
