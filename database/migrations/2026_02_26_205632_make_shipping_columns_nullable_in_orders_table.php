<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_address1')->nullable()->change();
            $table->string('shipping_city')->nullable()->change();
            $table->string('shipping_country')->nullable()->change();
            $table->string('shipping_postcode')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_address1')->nullable(false)->change();
            $table->string('shipping_city')->nullable(false)->change();
            $table->string('shipping_country')->nullable(false)->change();
            $table->string('shipping_postcode')->nullable(false)->change();
        });
    }
};
