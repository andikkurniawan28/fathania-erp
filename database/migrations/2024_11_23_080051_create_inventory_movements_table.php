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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->string('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreignId('inventory_adjust_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->text('description');
            $table->float('in');
            $table->float('out');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
