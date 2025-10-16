<?php

namespace App\Http\Controllers\Be\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['order.buyer', 'order.property']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                    ->orWhereHas('order.buyer', function ($sq) use ($request) {
                        $sq->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total_transactions' => Transaction::count(),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
            'success_transactions' => Transaction::whereIn('status', ['completed', 'success'])->count(),
            'failed_transactions' => Transaction::where('status', 'failed')->count(),
            'total_amount' => Transaction::whereIn('status', ['completed', 'success'])->sum('amount'),
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
        if (!$request->has(['date_from', 'date_to'])) {
            return view('pages.be.admin.transactions.report', compact('request'));
        }

        $request->validate([
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date', 'after_or_equal:date_from'],
        ]);

        $query = Transaction::with(['order.buyer', 'order.property'])
            ->whereDate('created_at', '>=', $request->date_from)
            ->whereDate('created_at', '<=', $request->date_to);

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $query->whereIn('status', ['completed', 'success']);

        $transactions = $query->orderBy('created_at', 'asc')->get();

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
