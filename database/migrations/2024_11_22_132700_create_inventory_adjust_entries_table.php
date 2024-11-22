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
        Schema::create('inventory_adjust_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_adjust_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained();
            $table->integer('item_order');
            $table->float('qty');
            $table->double('price');
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
        Schema::dropIfExists('inventory_adjust_entries');
    }
};
