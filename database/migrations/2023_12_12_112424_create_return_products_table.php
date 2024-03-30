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
        Schema::create('return_products', function (Blueprint $table) {
            $table->id();
            $table->string('return_no');
            $table->string('customer_id');
            $table->string('product_id');
            $table->string('quantity');
            $table->date('date');
            $table->string('status')->default('0')->comment('0=>inhouse, 1=>send supplier 2=>replaced to customer');
            $table->string('replace_status')->default('0')->comment('0=>pending, 1=>replaced to customer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_products');
    }
};
