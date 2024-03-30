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
        Schema::create('commission_details', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('employee_id');
            $table->tinyInteger('invoice_id');
            $table->tinyInteger('category_id');
            $table->double('amount');
            $table->date('date');
            $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_details');
    }
};
