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
        Schema::create('commission_targets', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('employee_id');
            $table->tinyInteger('category_id');
            $table->double('sales_target');
            $table->string('sales_commission');
            $table->tinyInteger('status')->default('1')->comment('1=>active,0=>inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_targets');
    }
};
