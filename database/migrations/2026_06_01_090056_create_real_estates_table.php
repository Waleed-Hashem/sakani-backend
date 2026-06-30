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
    Schema::create('real_estates', function (Blueprint $table) {
        $table->id();
        $table->enum('type', ['apartment', 'land']);
        $table->string('city');
        $table->string('area');
        $table->string('address');
        $table->decimal('price', 10, 2);       // 10 أرقام، منها 2 كسور
        $table->enum('status', ['for_sale', 'for_rent'])->default('for_rent');
        $table->foreignId('owner_id')
              ->constrained('users')           // FK → users.id
              ->onDelete('cascade');           // إذا حُذف المستخدم تُحذف عقاراته
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_estates');
    }
};
