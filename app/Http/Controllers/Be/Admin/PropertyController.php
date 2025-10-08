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
     * Update property status and notes.
     */
    public function updateStatus(Request $request, Property $property)
    {
        // Prevent updating completed properties (raffle already conducted)
        if ($property->status === 'completed') {
            return redirect()->route('admin.properties.index')
                ->with('error', 'Tidak dapat mengubah status properti yang sudah selesai (completed). Pengundian sudah dilakukan.');
        }

        $request->validate([
            'status' => ['required', 'in:draft,active,pending_draw,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $property->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        $statusLabels = [
            'draft' => 'draft',
            'active' => 'aktif',
            'pending_draw' => 'menunggu undian',
            'completed' => 'selesai',
            'cancelled' => 'dibatalkan'
        ];

        return redirect()->route('admin.properties.index')
            ->with('success', "Status properti berhasil diubah menjadi {$statusLabels[$request->status]}.");
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
     * Bulk update property status.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'property_ids' => ['required', 'array'],
            'property_ids.*' => ['exists:properties,id'],
            'status' => ['required', 'in:draft,active,pending_draw,completed,cancelled'],
        ]);

        // Check if any selected properties are completed
        $completedProperties = Property::whereIn('id', $request->property_ids)
            ->where('status', 'completed')
            ->count();

        if ($completedProperties > 0) {
            return redirect()->route('admin.properties.index')
                ->with('error', "Tidak dapat mengubah status {$completedProperties} properti yang sudah selesai (completed). Pengundian sudah dilakukan.");
        }

        Property::whereIn('id', $request->property_ids)
            ->update(['status' => $request->status]);

        $count = count($request->property_ids);
        $statusLabels = [
            'draft' => 'draft',
            'active' => 'aktif',
            'pending_draw' => 'menunggu undian',
            'completed' => 'selesai',
            'cancelled' => 'dibatalkan'
        ];

        return redirect()->route('admin.properties.index')
            ->with('success', "{$count} properti berhasil diubah statusnya menjadi {$statusLabels[$request->status]}.");
    }
}
