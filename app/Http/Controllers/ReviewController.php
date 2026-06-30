<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ReviewController extends Controller
{
    /**
     * دالة لحفظ تقييم جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        // 1. التحقق من صحة البيانات القادمة من الواجهات (Validation) لمنع النصب والأخطاء
        $request->validate([
            'owner_id' => 'required|exists:users,id', // يجب أن يكون صاحب العقار موجوداً في جدول المستخدمين
            'rating'   => 'required|integer|min:1|max:5', // التقييم يجب أن يكون رقماً بين 1 و 5
            'comment'  => 'nullable|string|max:500', // التعليق اختياري ولا يتجاوز 500 حرف
        ]);

        // 2. حفظ التقييم في قاعدة البيانات
        $review = Review::create([
            'user_id' => Auth::id() ?? 1, // معرف الزبون الحالي (إذا لم يسجل دخول بعد، نضع 1 مؤقتاً للتجربة)
            'owner_id' => $request->owner_id,
            'rating'   => $request->rating,
            'comment'  => $request->comment,
        ]);

        // 3. إرجاع رد لزميل الـ Frontend يؤكد نجاح العملية
        return response()->json([
            'message' => 'تم إضافة تقييمك بنجاح! شكراً لك.',
            'review'  => $review
        ], 201);
    }

    /**
     * دالة لعرض جميع تقييمات صاحب عقار معين وحساب متوسط تقييمه (Average Rating)
     */
    public function getOwnerReviews(int $owner_id)
    {
        // جلب جميع التقييمات الخاصة بهذا الشخص مع بيانات الزبون الذي قشيم
        $reviews = Review::where('owner_id', $owner_id)->with('user')->get();

        // حساب متوسط التقييم من 5 نجوم (مهمة جداً للواجهات)
        $averageRating = Review::where('owner_id', $owner_id)->avg('rating');

        return response()->json([
            'owner_id'       => $owner_id,
            'average_rating' => round($averageRating, 1), // تقريب النتيجة لرقم عشري واحد مثل 4.5
            'reviews_count'  => $reviews->count(),
            'reviews'        => $reviews
        ], 200);
    }
}
