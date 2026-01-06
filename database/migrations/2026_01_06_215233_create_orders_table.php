<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();  
            $table->string('bill_no')->unique(); // TN000005 etc.
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('mobile_number');
            
            
            $table->string('billing_address1');
            $table->string('billing_address2')->nullable();
            $table->string('billing_city');
            $table->string('billing_country');
            $table->string('billing_postcode');

             
            $table->string('shipping_address1');
            $table->string('shipping_address2')->nullable();
            $table->string('shipping_city');
            $table->string('shipping_country');
            $table->string('shipping_postcode');

            $table->enum('status', ['Pending', 'Processing', 'Completed', 'Cancelled'])->default('Pending');
            $table->enum('payment_status', ['Pending', 'Paid', 'Failed'])->default('Pending');

            $table->decimal('total', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
