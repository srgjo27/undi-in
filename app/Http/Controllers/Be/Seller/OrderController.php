<?php

namespace App\Http\Controllers\Be\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display listing of orders for the seller
     */
    public function index(Request $request)
    {
        $query = Order::bySeller(Auth::id())
            ->with(['buyer', 'property'])
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Note: payment_gateway filter removed since all orders are now manual transfer

        $orders = $query->paginate(10);

        return view('pages.be.seller.orders.index', compact('orders'));
    }

    /**
     * Show specific order details
     */
    public function show(Order $order)
    {
        // Ensure the order belongs to the seller's property
        if ($order->property->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['buyer', 'property', 'verifier']);

        return view('pages.be.seller.orders.show', compact('order'));
    }

    /**
     * Show orders awaiting verification
     */
    public function awaitingVerification()
    {
        $orders = Order::bySeller(Auth::id())
            ->awaitingVerification()
            ->with(['buyer', 'property'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.be.seller.orders.awaiting-verification', compact('orders'));
    }

    /**
     * Verify a manual transfer payment
     */
    public function verifyTransfer(Request $request, Order $order)
    {
        // Ensure the order belongs to the seller's property
        if ($order->property->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'verification_notes' => 'nullable|string|max:500',
        ]);

        try {
            if ($request->action === 'approve') {
                $order->verifyTransfer(Auth::id(), $request->verification_notes);

                return back()->with('success', 'Transfer berhasil diverifikasi. Order telah dikonfirmasi sebagai PAID.');
            } else {
                $order->rejectTransfer(Auth::id(), $request->verification_notes);

                return back()->with('success', 'Transfer ditolak. Order telah ditandai sebagai FAILED.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses verifikasi transfer. Silakan coba lagi.');
        }
    }

    /**
     * Show transfer proof image/PDF
     */
    public function showTransferProof(Order $order)
    {
        // Ensure the order belongs to the seller's property
        if ($order->property->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        if (!$order->transfer_proof) {
            abort(404, 'Transfer proof not found.');
        }

        $filePath = storage_path('app/public/' . $order->transfer_proof);

        if (!file_exists($filePath)) {
            abort(404, 'Transfer proof file not found.');
        }

        return response()->file($filePath);
    }
}
