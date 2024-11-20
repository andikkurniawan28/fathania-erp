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
        Schema::create('accounts', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('sub_account_id');
            $table->foreign('sub_account_id')->references('id')->on('sub_accounts');
            $table->foreignId('cash_flow_category_id')->nullable()->constrained();
            $table->string('name')->unique();
            // $table->string('normal_balance_id');
            // $table->foreign('normal_balance_id')->references('id')->on('normal_balances');
            $table->double('initial_balance');
            $table->boolean('is_payment_gateway')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
