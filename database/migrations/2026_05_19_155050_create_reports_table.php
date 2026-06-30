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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            // معرف الشخص الذي قام بتقديم الشكوى (الزبون)
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            
            // معرف الشخص المُشتكى عليه (صاحب العقار المحتال مثلاً)
            $table->foreignId('reported_user_id')->constrained('users')->onDelete('cascade');
            
            // سبب الشكوى (مثال: معلومات كاذبة، سعر وهمي، محاولة نصب)
            $table->string('reason');
            
            // تفاصيل إضافية يكتبها الزبون عن الشكوى (اختياري)
            $table->text('details')->nullable();
            
            // حالة الشكوى (قيد المراجعة من الآدمن، أو تم حلها وحظر المستخدم)
            $table->string('status')->default('pending'); // الافتراضي قيد الانتظار
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
