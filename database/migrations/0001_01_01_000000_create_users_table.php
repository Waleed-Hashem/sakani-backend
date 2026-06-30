<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_create_users_table.php
public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();                          // User_id — PK تلقائي
        $table->string('name');
        $table->string('email')->unique();     // لا يتكرر
        $table->string('password');
        $table->string('phone')->nullable();
        $table->enum('role', ['owner', 'tenant', 'investor', 'admin'])
              ->default('tenant');
        $table->timestamps();                  // created_at و updated_at تلقائياً
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
