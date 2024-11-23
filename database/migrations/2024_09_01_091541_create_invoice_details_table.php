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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreignId('material_id')->constrained();
            $table->integer('item_order');
            $table->float('qty');
            $table->double('price');
            $table->double('discount');
            $table->double('total');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
