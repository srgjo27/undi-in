<?php

namespace App\Http\Controllers\Be;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PropertyController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $query = Property::where('seller_id', Auth::id())->with(['images', 'orders']);

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search !== '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('city', 'like', '%' . $request->search . '%')
                    ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }

        $properties = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('pages.be.seller.properties.index', compact('properties'));
    }

    public function create()
    {
        return view('pages.be.seller.properties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'land_area' => 'required|integer|min:1',
            'building_area' => 'required|integer|min:1',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'facilities' => 'nullable|array',
            'price' => 'required|numeric|min:0',
            'coupon_price' => 'required|numeric|min:0',
            'max_coupons' => 'nullable|integer|min:1',
            'sale_start_date' => 'required|date|after_or_equal:today',
            'sale_end_date' => 'required|date|after:sale_start_date',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_image' => 'required|integer|min:0',
        ]);

        $property = new Property();
        $property->seller_id = Auth::id();
        $property->title = $request->title;
        $property->slug = Str::slug($request->title);
        $property->description = $request->description;
        $property->address = $request->address;
        $property->city = $request->city;
        $property->province = $request->province;
        $property->latitude = $request->latitude;
        $property->longitude = $request->longitude;
        $property->land_area = $request->land_area;
        $property->building_area = $request->building_area;
        $property->bedrooms = $request->bedrooms;
        $property->bathrooms = $request->bathrooms;
        $property->facilities = $request->facilities;
        $property->price = $request->price;
        $property->coupon_price = $request->coupon_price;
        $property->max_coupons = $request->max_coupons;
        $property->sale_start_date = $request->sale_start_date;
        $property->sale_end_date = $request->sale_end_date;
        $property->status = 'draft';
        $property->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties/' . $property->id, 'public');

                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $path,
                    'is_primary' => $index == $request->primary_image,
                    'caption' => $request->input("captions.{$index}")
                ]);
            }
        }

        return redirect()->route('seller.properties.index')
            ->with('success', 'Properti berhasil ditambahkan!');
    }

    public function show(Property $property)
    {
        $this->authorize('view', $property);

        $property->load(['images', 'orders']);

        return view('pages.seller.properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        $this->authorize('update', $property);

        return view('pages.seller.properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $this->authorize('update', $property);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'land_area' => 'required|integer|min:1',
            'building_area' => 'required|integer|min:1',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'facilities' => 'nullable|array',
            'price' => 'required|numeric|min:0',
            'coupon_price' => 'required|numeric|min:0',
            'max_coupons' => 'nullable|integer|min:1',
            'sale_start_date' => 'required|date',
            'sale_end_date' => 'required|date|after:sale_start_date',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_image' => 'nullable|integer|min:0',
        ]);

        $property->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'land_area' => $request->land_area,
            'building_area' => $request->building_area,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'facilities' => $request->facilities,
            'price' => $request->price,
            'coupon_price' => $request->coupon_price,
            'max_coupons' => $request->max_coupons,
            'sale_start_date' => $request->sale_start_date,
            'sale_end_date' => $request->sale_end_date,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties/' . $property->id, 'public');

                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $path,
                    'is_primary' => $index == $request->primary_image,
                    'caption' => $request->input("captions.{$index}")
                ]);
            }
        }

        return redirect()->route('seller.properties.index')
            ->with('success', 'Properti berhasil diperbarui!');
    }

    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);

        if ($property->orders()->count() > 0) {
            return redirect()->route('seller.properties.index')
                ->with('error', 'Tidak dapat menghapus properti yang sudah memiliki pesanan!');
        }

        $property->delete();

        return redirect()->route('seller.properties.index')
            ->with('success', 'Properti berhasil dihapus!');
    }

    public function updateStatus(Request $request, Property $property)
    {
        $this->authorize('update', $property);

        $request->validate([
            'status' => ['required', Rule::in(['draft', 'active', 'pending_draw', 'completed', 'cancelled'])]
        ]);

        $property->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Status properti berhasil diperbarui!');
    }

    public function deleteImage(PropertyImage $image)
    {
        $property = $image->property;
        $this->authorize('update', $property);

        if ($property->images()->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Properti harus memiliki minimal satu gambar!'
            ], 400);
        }

        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil dihapus!'
        ]);
    }

    public function setPrimaryImage(PropertyImage $image)
    {
        $property = $image->property;
        $this->authorize('update', $property);

        $property->images()->update(['is_primary' => false]);

        $image->update(['is_primary' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Gambar utama berhasil diperbarui!'
        ]);
    }
}
