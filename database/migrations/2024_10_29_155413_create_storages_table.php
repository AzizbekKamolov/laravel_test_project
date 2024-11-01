<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('storages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('refund_residual');
            $table->unsignedInteger('sell_residual');
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('refunded_amount')->default(0);
            $table->unsignedBigInteger('batch_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storages');
    }
};
