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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->string('year');
            $table->string('month');
            $table->date('from');
            $table->date('to');
            $table->double('salary');
            $table->float('attendance_credit');
            $table->double('overtime');
            $table->float('overtime_credit');
            $table->double('leave');
            $table->float('leave_credit');
            $table->double('incentive');
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
        Schema::dropIfExists('payrolls');
    }
};
