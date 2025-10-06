<?php

namespace App\Http\Controllers\Be;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\User;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } elseif ($user->role === 'seller') {
            return $this->sellerDashboard();
        }

        return view('pages.web.index');
    }

    private function sellerDashboard()
    {
        $properties = Property::where('seller_id', Auth::id())->get();

        $stats = [
            'total_properties' => $properties->count(),
            'active_properties' => $properties->where('status', 'active')->count(),
            'draft_properties' => $properties->where('status', 'draft')->count(),
            'completed_properties' => $properties->where('status', 'completed')->count(),
            'total_coupons_sold' => $properties->sum('sold_coupons'),
            'total_earnings' => $properties->sum(function ($property) {
                return $property->sold_coupons * $property->coupon_price;
            }),
        ];

        $recent_properties = $properties->sortByDesc('created_at')->take(5);

        return view('pages.seller.dashboard.index', compact('stats', 'recent_properties'));
    }

    private function adminDashboard()
    {
        // Overall statistics
        $stats = [
            'total_users' => User::count(),
            'total_sellers' => User::where('role', 'seller')->count(),
            'total_buyers' => User::where('role', 'buyer')->count(),
            'active_users' => User::whereNotNull('email_verified_at')->count(),

            'total_properties' => Property::count(),
            'pending_properties' => Property::where('verification_status', 'pending')->count(),
            'approved_properties' => Property::where('verification_status', 'approved')->count(),
            'active_properties' => Property::where('status', 'active')->count(),

            'total_orders' => Order::count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),

            'total_transactions' => Transaction::count(),
            'successful_transactions' => Transaction::where('status', 'success')->count(),
            'total_revenue' => Transaction::where('status', 'success')->sum('amount'),

            'total_coupons' => Coupon::count(),
            'winner_coupons' => Coupon::where('is_winner', true)->count(),
        ];

        // Recent activities
        $recent_users = User::orderBy('created_at', 'desc')->limit(5)->get();
        $recent_properties = Property::with('seller')->orderBy('created_at', 'desc')->limit(5)->get();
        $recent_transactions = Transaction::with(['order.buyer', 'order.property'])
            ->orderBy('created_at', 'desc')->limit(5)->get();

        // Charts data
        $monthly_revenue = Transaction::where('status', 'success')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $property_status_chart = Property::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        return view('pages.be.admin.dashboard.index', compact(
            'stats',
            'recent_users',
            'recent_properties',
            'recent_transactions',
            'monthly_revenue',
            'property_status_chart'
        ));
    }
}
