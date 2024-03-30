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
        Schema::create('ingredient_stores', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_id')->nullable();
            $table->integer('ingredient_id')->nullable();
            $table->string('quantity')->nullable();
            $table->double('unit_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_stores');
    }
};
