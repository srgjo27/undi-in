<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    /**
     * Show property detail and order form
     */
    public function show(Property $property)
    {
        if ($property->status !== 'active') {
            return redirect()->route('buyer.home')
                ->with('error', 'Properti ini tidak tersedia untuk pembelian.');
        }

        if (!$property->hasAvailableCoupons()) {
            return redirect()->route('buyer.home')
                ->with('error', 'Kuota kupon untuk properti ini sudah habis.');
        }

        $property->load(['seller', 'images']);
        $availableCoupons = $property->available_coupons;

        return view('pages.web.properties.show', compact('property', 'availableCoupons'));
    }

    /**
     * Create new order
     */
    public function store(Request $request, Property $property)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        if ($property->status !== 'active') {
            return back()->with('error', 'Properti ini tidak tersedia untuk pembelian.');
        }

        $validation = $property->canPurchaseQuantity($request->quantity);
        if (!$validation['valid']) {
            return back()->with('error', $validation['message']);
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'buyer_id' => Auth::id(),
                'property_id' => $property->id,
                'quantity' => $request->quantity,
                'total_price' => $property->coupon_price * $request->quantity,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('buyer.orders.payment', $order)
                ->with('success', 'Order berhasil dibuat. Silakan lakukan transfer manual ke rekening seller.');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Gagal membuat order. Silakan coba lagi.');
        }
    }

    /**
     * Show payment page
     */
    public function payment(Order $order)
    {
        if ($order->buyer_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        if (!in_array($order->status, ['pending', 'awaiting_verification'])) {
            return redirect()->route('buyer.orders.show', $order)
                ->with('info', 'Order ini sudah diproses.');
        }

        $order->load(['property.images', 'property.seller']);

        return view('pages.web.orders.payment', compact('order'));
    }

    /**
     * Show order details
     */
    public function orderShow(Order $order)
    {
        if ($order->buyer_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        $order->load(['property.images', 'property.seller', 'coupons', 'transactions']);

        return view('pages.web.orders.show', compact('order'));
    }

    /**
     * List user's orders
     */
    public function index(Request $request)
    {
        $query = Order::where('buyer_id', Auth::id())
            ->with(['property.images', 'coupons'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('pages.web.orders.index', compact('orders'));
    }

    /**
     * Show user's coupons
     */
    public function coupons(Request $request)
    {
        $query = \App\Models\Coupon::where('buyer_id', Auth::id())
            ->with(['property.images', 'order'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('is_winner')) {
            $query->where('is_winner', $request->is_winner);
        }

        $coupons = $query->paginate(10);

        return view('pages.web.coupons.index', compact('coupons'));
    }

    /**
     * Cancel pending order
     */
    public function cancel(Order $order)
    {
        if ($order->buyer_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Order ini tidak dapat dibatalkan.');
        }

        try {
            $order->update(['status' => 'cancelled']);

            return redirect()->route('buyer.orders.index')
                ->with('success', 'Order berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan order.');
        }
    }

    /**
     * Retry payment for failed order (now just resets to pending for manual transfer)
     */
    public function retryPayment(Order $order)
    {
        if ($order->buyer_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        if (!in_array($order->status, ['failed', 'cancelled'])) {
            return back()->with('error', 'Order ini tidak dapat diulang pembayarannya.');
        }

        try {
            $order->update([
                'status' => 'pending',
                'transfer_proof' => null,
                'seller_bank_info' => null,
                'verified_by' => null,
                'verified_at' => null,
                'verification_notes' => null,
            ]);

            return redirect()->route('buyer.orders.payment', $order)
                ->with('success', 'Order telah direset. Silakan upload bukti transfer yang baru.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran ulang: ' . $e->getMessage());
        }
    }

    /**
     * This method is no longer needed since we use manual transfer
     * Kept for backward compatibility but returns error
     */
    public function refreshToken(Order $order)
    {
        return response()->json([
            'success' => false,
            'message' => 'Token refresh tidak diperlukan untuk transfer manual.'
        ], 400);
    }

    /**
     * Upload transfer proof for manual payment
     */
    public function uploadTransferProof(Request $request, Order $order)
    {
        if ($order->buyer_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Bukti transfer hanya dapat diupload untuk order dengan status pending.');
        }

        $request->validate([
            'transfer_proof' => [
                'required',
                'file',
                'mimes:jpeg,jpg,png,pdf',
                'max:2048'
            ]
        ], [
            'transfer_proof.required' => 'Bukti transfer wajib diupload.',
            'transfer_proof.file' => 'File bukti transfer tidak valid.',
            'transfer_proof.mimes' => 'Format file harus JPG, PNG, atau PDF.',
            'transfer_proof.max' => 'Ukuran file maksimal 2MB.'
        ]);

        try {
            $transferProofPath = $request->file('transfer_proof')->store('transfer-proofs', 'public');

            $seller = $order->property->seller;
            $sellerBankInfo = null;
            if ($seller && $seller->hasCompleteBankInfo()) {
                $sellerBankInfo = [
                    'bank_name' => $seller->bank_name,
                    'account_number' => $seller->bank_account_number,
                    'account_name' => $seller->bank_account_name,
                ];
            }

            $order->markAwaitingVerification($transferProofPath, $sellerBankInfo);

            return back()->with('success', 'Bukti transfer berhasil diupload. Seller akan memverifikasi pembayaran dalam 1x24 jam.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupload bukti transfer. Silakan coba lagi.');
        }
    }
}
