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
        Schema::create('vacations', function (Blueprint $table) {
            $table->uuid('id', 36)->primary();
            $table->foreignUuid('employee_id')->references('id')->on('employees');
            $table->date('initial_date')->nullable();
            $table->date('final_date')->nullable();
            $table->string('comments')->nullable();
            $table->enum('state', ['pending', 'approved', 'refused'])->default('pending');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacations');
    }
};
