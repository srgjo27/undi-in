<?php

namespace App\Http\Controllers\Be\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Order;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['order.buyer', 'order.property']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by transaction ID or buyer name
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                    ->orWhereHas('order.buyer', function ($sq) use ($request) {
                        $sq->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics for dashboard
        $stats = [
            'total_transactions' => Transaction::count(),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
            'success_transactions' => Transaction::where('status', 'success')->count(),
            'failed_transactions' => Transaction::where('status', 'failed')->count(),
            'total_amount' => Transaction::where('status', 'success')->sum('amount'),
        ];

        return view('pages.be.admin.transactions.index', compact('transactions', 'stats'));
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['order.buyer', 'order.property', 'order.coupons']);
        return view('pages.be.admin.transactions.show', compact('transaction'));
    }

    /**
     * Update transaction status.
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => ['required', 'in:pending,processing,success,failed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $transaction->update(['status' => $request->status]);

        // Update order status based on transaction status
        if ($request->status === 'success') {
            $transaction->order->update(['payment_status' => 'paid', 'status' => 'completed']);
        } elseif (in_array($request->status, ['failed', 'cancelled'])) {
            $transaction->order->update(['payment_status' => 'failed', 'status' => 'cancelled']);
        }

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Status transaksi berhasil diupdate.');
    }

    /**
     * Generate transaction report.
     */
    public function report(Request $request)
    {
        $request->validate([
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date', 'after_or_equal:date_from'],
        ]);

        $transactions = Transaction::with(['order.buyer', 'order.property'])
            ->whereDate('created_at', '>=', $request->date_from)
            ->whereDate('created_at', '<=', $request->date_to)
            ->where('status', 'success')
            ->orderBy('created_at', 'asc')
            ->get();

        $summary = [
            'total_transactions' => $transactions->count(),
            'total_amount' => $transactions->sum('amount'),
            'by_payment_method' => $transactions->groupBy('payment_method')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'amount' => $group->sum('amount')
                ];
            }),
        ];

        return view('pages.be.admin.transactions.report', compact('transactions', 'summary', 'request'));
    }
}
