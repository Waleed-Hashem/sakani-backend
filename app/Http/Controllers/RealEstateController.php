<?php

namespace App\Http\Controllers;

use App\Models\RealEstate;
use Illuminate\Http\Request;

class RealEstateController extends Controller
{
    // عرض كل العقارات
public function index(Request $request)
{
    $query = RealEstate::with(['owner', 'images']);

    if ($request->filled('owner_id')) {
        $query->where('owner_id', $request->owner_id);
    }

    $estates = $query->paginate(10);

    return response()->json($estates);
}

    // عرض عقار واحد
    public function show(RealEstate $estate)
    {
        return response()->json($estate->load(['owner', 'images']));
    }


    // إضافة عقار جديد
    public function store(Request $request)
    {
        // التحقق من البيانات
        $validated = $request->validate([
            'type'     => 'required|in:apartment,land',
            'city'     => 'required|string|max:100',
            'area'     => 'required|string|max:100',
            'address'  => 'required|string|max:255',
            'price'    => 'required|numeric|min:1',
            'status'   => 'required|in:for_sale,for_rent',
        ]);

        // العقار يُنسب دائماً للمستخدم المسجّل دخوله، وليس لقيمة قادمة من الواجهة
        $validated['owner_id'] = $request->user()->id;

        // إنشاء العقار
        $estate = RealEstate::create($validated);

        // رفع الصور
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('real_estate_images', 'public');

                $estate->images()->create([
                    'image_path' => $path
                ]);
            }
        }

        return response()->json([
            'message' => 'تم إضافة العقار بنجاح',
            'estate'  => $estate->load('images'),
        ], 201);
    }
public function search(Request $request)
{
    $query = RealEstate::with('images');

    if ($request->filled('type')) {
        $query->ofType($request->type);
    }

    if ($request->filled('city')) {
        $query->inCity($request->city);
    }

    if ($request->filled('min_price') && $request->filled('max_price')) {
        $query->priceRange($request->min_price, $request->max_price);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    return response()->json($query->paginate(10));
}

    // تعديل عقار
    public function update(Request $request, RealEstate $estate)
    {
        $validated = $request->validate([
            'type'    => 'sometimes|in:apartment,land',
            'city'    => 'sometimes|string|max:100',
            'area'    => 'sometimes|string|max:100',
            'address' => 'sometimes|string|max:255',
            'price'   => 'sometimes|numeric|min:1',
            'status'  => 'sometimes|in:for_sale,for_rent',
        ]);

        $estate->update($validated);

        return response()->json([
            'message' => 'تم تعديل العقار بنجاح',
            'estate'  => $estate->load('images'),
        ]);
    }

    // حذف عقار
    public function destroy(RealEstate $estate)
    {
        $estate->delete();

        return response()->json([
            'message' => 'تم حذف العقار بنجاح',
        ]);
    }
}
