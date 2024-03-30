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
        Schema::create('sales_profits', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('invoice_id');
            $table->tinyInteger('product_id');
            $table->tinyInteger('purchase_id');
            $table->double('unit_price_purchase');
            $table->double('unit_price_sales');
            $table->double('discount_per_unit');
            $table->double('selling_qty');
            $table->double('profit_per_unit');
            $table->double('profit');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_profits');
    }
};
