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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreignId('shift_id')->constrained();
            $table->time('check_in');
            $table->time('check_out')->nullable();
            $table->time('break')->nullable();
            $table->float('early_check_in')->nullable(); // Masuk lebih awal
            $table->float('late_check_in')->nullable();  // Masuk terlambat
            $table->float('early_check_out')->nullable(); // Pulang lebih awal
            $table->float('late_check_out')->nullable();  // Pulang terlambat
            $table->float('early_break')->nullable();    // Istirahat lebih awal
            $table->float('late_break')->nullable();     // Istirahat terlambat
            $table->float('credit');
            $table->double('basic_salary');
            $table->double('net_salary');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
