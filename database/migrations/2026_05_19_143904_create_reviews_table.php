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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // معرف الزبون الذي كتب التقييم (يرتبط بجدول المستخدمين)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            
            // معرف صاحب العقار الذي تم تقييمه (يرتبط بجدول المستخدمين أيضاً)
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade'); 
            
            // التقييم رقم من 1 إلى 5 نجوم
            $table->unsignedTinyInteger('rating'); 
            
            // تعليق الزبون ونص التقييم (nullable تعني اختياري يمكن تركه فارغاً)
            $table->text('comment')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
