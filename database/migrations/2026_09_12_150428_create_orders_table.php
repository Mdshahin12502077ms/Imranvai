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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique()->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('customer_info_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_variation_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('sub_total', 10, 2);
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('discount', 10, 2)->default(0.00)->nullable();
            $table->decimal('grand_total', 10, 2);
            $table->string('transaction_id')->nullable();
            $table->enum('payment_method', ['cash_on_delivery', 'stripe', 'paypal'])->default('cash_on_delivery');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->enum('order_status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_info_id')->references('id')->on('customer_infos')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_variation_id')->references('id')->on('product_variations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
