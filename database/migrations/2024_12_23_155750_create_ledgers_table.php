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
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('journal_id')->nullable();
            $table->foreign('journal_id')->references('id')->on('journals')->onDelete('cascade');
            $table->string('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->string('repayment_id')->nullable();
            $table->foreign('repayment_id')->references('id')->on('repayments')->onDelete('cascade');
            $table->foreignId('inventory_adjust_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('account_id');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreignId('user_id')->constrained();
            $table->text('description');
            $table->double('debit');
            $table->double('credit');
            $table->boolean('is_closing_entry')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledgers');
    }
};
