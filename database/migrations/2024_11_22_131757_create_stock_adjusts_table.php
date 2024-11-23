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
        Schema::create('stock_adjusts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('stock_normal_balance_id')->nullable();
            $table->foreign('stock_normal_balance_id')->references('id')->on('normal_balances');
            $table->string('profit_loss_account_id');
            $table->foreign('profit_loss_account_id')->references('id')->on('accounts');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjusts');
    }
};
