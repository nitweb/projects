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
        Schema::create('packaging_metas', function (Blueprint $table) {
            $table->id();
            $table->string('package_type')->nullable();
            $table->integer('packaging_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('quantity')->nullable();
            $table->double('unit_price')->nullable();
            $table->double('total_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packaging_metas');
    }
};
