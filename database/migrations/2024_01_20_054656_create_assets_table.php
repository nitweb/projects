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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->tinyInteger('cat_id')->nullable();
            $table->tinyInteger('sub_cat_id')->nullable();
            $table->double('price')->nullable();
            $table->string('longevity')->nullable();
            $table->string('type')->nullable();
            $table->tinyInteger('status')->default('1')->comment('1=>yes, 0=>no');
            $table->date('purchase_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
