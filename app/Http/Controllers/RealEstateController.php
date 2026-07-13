<?php
namespace App\Http\Controllers;

use App\Models\RealEstate;
use Illuminate\Http\Request;

class RealEstateController extends Controller
{
    public function index(Request $request)
    {
        $query = RealEstate::with(['owner', 'images']);

        if ($request->filled('owner_id')) {
            $query->where('owner_id', $request->owner_id);
        }
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // ✅ get() بدل paginate() لتجنب مشكلة data.data في JS
        $estates = $query->latest()->get();

        return response()->json($estates);
    }

    public function show(RealEstate $estate)
    {
        return response()->json($estate->load(['owner', 'images']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // ✅ أضفنا 'shared' + حقول إضافية
            'type'        => 'required|in:apartment,land,shared',
            'city'        => 'required|string|max:100',
            'area'        => 'required|string|max:100',
            'address'     => 'required|string|max:255',
            'price'       => 'required|numeric|min:1',
            'status'      => 'required|in:for_sale,for_rent',
            'size'        => 'nullable|numeric|min:1',
            'rooms'       => 'nullable|string|max:20',
            'description' => 'nullable|string|max:2000',
            'gender'      => 'nullable|in:ذكور,إناث,مختلط',
            'images.*'    => 'nullable|image|max:5120',
        ]);

        $validated['owner_id'] = $request->user()->id;

        $estate = RealEstate::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('real_estate_images', 'public');
                $estate->images()->create(['image_path' => $path]);
            }
        }

        return response()->json([
            'message' => 'تم إضافة العقار بنجاح',
            'estate'  => $estate->load('images'),
        ], 201);
    }

    public function update(Request $request, RealEstate $estate)
    {
        // ✅ تحقق أن المالك هو نفسه
        if ($estate->owner_id !== $request->user()->id) {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

        $validated = $request->validate([
            'type'        => 'sometimes|in:apartment,land,shared',
            'city'        => 'sometimes|string|max:100',
            'area'        => 'sometimes|string|max:100',
            'address'     => 'sometimes|string|max:255',
            'price'       => 'sometimes|numeric|min:1',
            'status'      => 'sometimes|in:for_sale,for_rent',
            'size'        => 'nullable|numeric|min:1',
            'rooms'       => 'nullable|string|max:20',
            'description' => 'nullable|string|max:2000',
            'gender'      => 'nullable|in:ذكور,إناث,مختلط',
            'images.*'    => 'nullable|image|max:5120',
        ]);

        $estate->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('real_estate_images', 'public');
                $estate->images()->create(['image_path' => $path]);
            }
        }

        return response()->json([
            'message' => 'تم تعديل العقار بنجاح',
            'estate'  => $estate->load('images'),
        ]);
    }

    public function destroy(Request $request, RealEstate $estate)
    {
        // ✅ تحقق أن المالك أو admin
        $user = $request->user();
        if ($estate->owner_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

        $estate->delete();

        return response()->json(['message' => 'تم حذف العقار بنجاح']);
    }

    public function search(Request $request)
    {
        $query = RealEstate::with('images');

        if ($request->filled('type'))   $query->where('type', $request->type);
        if ($request->filled('city'))   $query->where('city', $request->city);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        return response()->json($query->latest()->get());
    }
}
