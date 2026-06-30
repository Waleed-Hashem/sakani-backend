<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RealEstateController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PropertyFinancialController;
use App\Http\Controllers\SubscriptionController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// تصفح العقارات (عام لأي زائر)
Route::get('/real-estates', [RealEstateController::class, 'index']);
Route::get('/real-estates/{estate}', [RealEstateController::class, 'show']);

// البحث
Route::get('/search/real-estates', [RealEstateController::class, 'search']);

// تقييمات المالك
Route::get('/owners/{id}/reviews', [ReviewController::class, 'getOwnerReviews']);

// فلترة حسب القدرة المالية
Route::post('/real-estates/filter-by-budget', [PropertyFinancialController::class, 'filterByBudget']);

// باقات الاشتراك (عرض فقط، الاشتراك الفعلي يتطلب تسجيل دخول)
Route::get('/packages', [SubscriptionController::class, 'packages']);

/*
|--------------------------------------------------------------------------
| Protected Routes (auth:sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // المستخدم الحالي
    Route::get('/user', [AuthController::class, 'user']);

    // CRUD العقارات (الإضافة/التعديل/الحذف تتطلب تسجيل دخول)
    Route::post('/real-estates', [RealEstateController::class, 'store']);
    Route::put('/real-estates/{estate}', [RealEstateController::class, 'update']);
    Route::delete('/real-estates/{estate}', [RealEstateController::class, 'destroy']);

    // إضافة تقييم
    Route::post('/reviews', [ReviewController::class, 'store']);

    // الشكاوى
    Route::post('/reports', [ReportController::class, 'store']);
    Route::get('/admin/reports', [ReportController::class, 'index']);
    Route::put('/admin/reports/{id}/status', [ReportController::class, 'updateStatus']);

    // الاشتراكات
    Route::get('/subscriptions/current', [SubscriptionController::class, 'current']);
    Route::post('/subscriptions/subscribe', [SubscriptionController::class, 'subscribe']);

    // تسجيل خروج
    Route::post('/logout', [AuthController::class, 'logout']);
});
