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
        Schema::create('invoice_categories', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name')->unique();
            $table->string('deal_with');
            $table->string('item');
            $table->string('price_used');
            $table->string('stock_normal_balance_id')->nullable();
            $table->foreign('stock_normal_balance_id')->references('id')->on('normal_balances');
            $table->string('subtotal_account_id');
            $table->foreign('subtotal_account_id')->references('id')->on('accounts');
            $table->string('subtotal_normal_balance_id');
            $table->foreign('subtotal_normal_balance_id')->references('id')->on('normal_balances');
            $table->string('taxes_account_id');
            $table->foreign('taxes_account_id')->references('id')->on('accounts');
            $table->string('taxes_normal_balance_id');
            $table->foreign('taxes_normal_balance_id')->references('id')->on('normal_balances');
            $table->string('freight_account_id');
            $table->foreign('freight_account_id')->references('id')->on('accounts');
            $table->string('freight_normal_balance_id');
            $table->foreign('freight_normal_balance_id')->references('id')->on('normal_balances');
            $table->string('discount_account_id');
            $table->foreign('discount_account_id')->references('id')->on('accounts');
            $table->string('discount_normal_balance_id');
            $table->foreign('discount_normal_balance_id')->references('id')->on('normal_balances');
            $table->string('grand_total_account_id');
            $table->foreign('grand_total_account_id')->references('id')->on('accounts');
            $table->string('grand_total_normal_balance_id');
            $table->foreign('grand_total_normal_balance_id')->references('id')->on('normal_balances');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_categories');
    }
};
