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
        Schema::create('invoices', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('invoice_category_id');
            $table->foreign('invoice_category_id')->references('id')->on('invoice_categories');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('payment_term_id')->constrained();
            $table->foreignId('tax_rate_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('supplier_id')->nullable()->constrained();
            $table->foreignId('customer_id')->nullable()->constrained();
            $table->date('valid_until');
            $table->double('subtotal');
            $table->double('taxes');
            $table->double('freight');
            $table->double('discount');
            $table->double('grand_total');
            $table->double('paid');
            $table->double('left');
            // $table->double('cashback');
            $table->string('payment_gateway_id')->nullable();
            $table->foreign('payment_gateway_id')->references('id')->on('accounts');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
