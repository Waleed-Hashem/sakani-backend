<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyFinancialController extends Controller
{
    /**
     * فلترة العقارات حسب:
     * - السعر (min/max)
     * - حالة العقار (for_sale / for_rent)
     * - نوع العقار (apartment / land)
     */
    public function filterByBudget(Request $request)
    {
        // 1. التحقق من البيانات القادمة من الواجهة
        $request->validate([
            'status'      => 'required|in:for_sale,for_rent',
            'type'        => 'required|in:apartment,land',
            'max_budget'  => 'required|numeric|min:0',
            'min_budget'  => 'nullable|numeric|min:0',
        ]);

        $status     = $request->status;
        $type       = $request->type;
        $maxBudget  = $request->max_budget;
        $minBudget  = $request->min_budget ?? 0;

        // 2. الاستعلام من جدول real_estates
        $properties = DB::table('real_estates')
            ->where('status', $status)
            ->where('type', $type)
            ->whereBetween('price', [$minBudget, $maxBudget])
            ->orderBy('price', 'asc')
            ->get();

        // 3. إرجاع النتائج
        return response()->json([
            'search_criteria' => [
                'status' => $status,
                'type'   => $type,
                'max_allowed_price' => $maxBudget,
                'min_allowed_price' => $minBudget
            ],
            'matching_properties_count' => $properties->count(),
            'properties' => $properties
        ], 200);
    }
}
