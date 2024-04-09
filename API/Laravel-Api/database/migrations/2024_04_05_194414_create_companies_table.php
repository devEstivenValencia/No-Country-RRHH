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
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->references('id')->on('users');
            $table->string('company_name')->nullable();
            $table->json('working_week')->nullable();
            $table->json('roles')->nullable();
            $table->boolean('verified')->default(false);
            $table->integer('number_of_employees')->default(0);
            $table->string('sector')->nullable();
            $table->json('location')->nullable();
            $table->json('contact')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
