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
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->references('id')->on('users');
            $table->foreignUuid('company_id')->references('id')->on('companies');
            $table->string('name')->nullable();
            $table->string('id_legal')->nullable();
            $table->string('employee_code')->nullable();
            $table->json('address')->nullable();
            $table->json('contact')->nullable();
            $table->date('admission_date')->nullable();
            $table->date('finish_date')->nullable();
            $table->enum('state', ['active', 'inactive'])->nullable();
            $table->json('role')->nullable();
            $table->timestamp('created_at')->useCurrent();
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
