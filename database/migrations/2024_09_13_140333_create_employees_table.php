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
        Schema::create('employees', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name')->unique();
            $table->text('address');
            $table->string('place_of_birth');
            $table->date('birthday');
            $table->foreignId('title_id')->constrained();
            $table->foreignId('employee_status_id')->constrained();
            $table->foreignId('education_id')->constrained();
            $table->foreignId('campus_id')->constrained();
            $table->foreignId('major_id')->constrained();
            $table->foreignId('religion_id')->constrained();
            $table->foreignId('marital_status_id')->constrained();
            $table->foreignId('bank_id')->constrained();
            $table->integer('leave')->default(6);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
