<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPackage;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    // عرض كل الباقات المتاحة
    public function packages()
    {
        return response()->json(SubscriptionPackage::all());
    }

    // عرض الاشتراك الحالي للمستخدم المسجل دخوله
    public function current(Request $request)
    {
        $subscription = UserSubscription::where('user_id', $request->user()->id)
            ->where('status', 'active')
            ->latest()
            ->with('package')
            ->first();

        if (!$subscription) {
            return response()->json([
                'package'   => null,
                'ends_at'   => null,
                'is_active' => false,
            ]);
        }

        return response()->json([
            'package'   => $subscription->package,
            'ends_at'   => $subscription->ends_at,
            'is_active' => $subscription->isActive(),
        ]);
    }

    // الاشتراك في باقة
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:subscription_packages,id',
        ]);

        $package = SubscriptionPackage::findOrFail($validated['package_id']);

        $subscription = UserSubscription::subscribe(
            $request->user()->id,
            $package->id,
            $package->price
        );

        return response()->json([
            'message'   => 'تم الاشتراك بنجاح',
            'package'   => $package,
            'ends_at'   => $subscription->ends_at,
        ], 201);
    }
}
