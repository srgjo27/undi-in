<?php

namespace App\Http\Controllers\Be\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of properties.
     */
    public function index(Request $request)
    {
        $query = Property::with(['seller', 'images']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by verification status
        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        // Search by title or seller name
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhereHas('seller', function ($sq) use ($request) {
                        $sq->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $properties = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('pages.be.admin.properties.index', compact('properties'));
    }

    /**
     * Display the specified property.
     */
    public function show(Property $property)
    {
        $property->load(['seller', 'images', 'orders.buyer']);
        return view('pages.be.admin.properties.show', compact('property'));
    }

    /**
     * Update property verification status.
     */
    public function updateVerification(Request $request, Property $property)
    {
        $request->validate([
            'verification_status' => ['required', 'in:pending,approved,rejected'],
            'verification_notes' => ['nullable', 'string'],
        ]);

        $property->update([
            'verification_status' => $request->verification_status,
            'verification_notes' => $request->verification_notes,
        ]);

        $status = [
            'pending' => 'dikembalikan ke pending',
            'approved' => 'disetujui',
            'rejected' => 'ditolak'
        ];

        return redirect()->route('admin.properties.index')
            ->with('success', "Properti berhasil {$status[$request->verification_status]}.");
    }

    /**
     * Remove the specified property from storage.
     */
    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()->route('admin.properties.index')
            ->with('success', 'Properti berhasil dihapus.');
    }

    /**
     * Bulk update verification status.
     */
    public function bulkUpdateVerification(Request $request)
    {
        $request->validate([
            'property_ids' => ['required', 'array'],
            'property_ids.*' => ['exists:properties,id'],
            'verification_status' => ['required', 'in:approved,rejected'],
        ]);

        Property::whereIn('id', $request->property_ids)
            ->update(['verification_status' => $request->verification_status]);

        $count = count($request->property_ids);
        $status = $request->verification_status === 'approved' ? 'disetujui' : 'ditolak';

        return redirect()->route('admin.properties.index')
            ->with('success', "{$count} properti berhasil {$status}.");
    }
}
