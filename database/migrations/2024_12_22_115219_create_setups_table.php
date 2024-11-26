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
        Schema::create('setups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->constrained();
            $table->string('app_name');
            $table->string('company_name');
            $table->string('company_logo')->nullable();
            $table->string('retained_earning_id');
            $table->foreign('retained_earning_id')->references('id')->on('accounts');
            $table->string('material_inventory_id');
            $table->foreign('material_inventory_id')->references('id')->on('accounts');
            // $table->double('daily_wage')->nullable();
            // $table->double('hourly_wage')->nullable();
            // $table->double('hourly_overtime')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setups');
    }
};
