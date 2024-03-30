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
        Schema::create('supplier_replace_histories', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('replace_id');
            $table->tinyInteger('supplier_id');
            $table->tinyInteger('customer_id');
            $table->tinyInteger('product_id');
            $table->string('quantity');
            $table->date('date');
            $table->string('status')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_replace_histories');
    }
};
