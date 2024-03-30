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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('paid_status', 51)->nullable();
            $table->double('paid_amount')->nullable();
            $table->double('due_amount')->nullable();
            $table->double('discount_amount')->nullable();
            $table->string('discount_type')->nullable();
            $table->string('delivery_note')->nullable();
            $table->double('delivery_charge')->nullable();
            $table->double('total_amount')->nullable();
            $table->tinyInteger('tax_id')->nullable();
            $table->double('vat_tax')->nullable();
            $table->double('vat_amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};