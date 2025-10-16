<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Order;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalOrders = Order::where('buyer_id', $user->id)->count();
        $totalSpent = Order::where('buyer_id', $user->id)
            ->where('status', 'paid')
            ->sum('total_price');
        $activeCoupons = Coupon::where('buyer_id', $user->id)
            ->where('is_winner', false)
            ->count();

        $featuredProperties = Property::with(['images', 'orders'])
            ->where('status', 'active')
            ->latest()
            ->limit(6)
            ->get();

        $recentOrders = Order::with('property')
            ->where('buyer_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('pages.web.home', compact(
            'totalOrders',
            'totalSpent',
            'activeCoupons',
            'featuredProperties',
            'recentOrders'
        ));
    }

    public function allProperties(Request $request)
    {
        $query = Property::with(['images', 'orders', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('province', 'like', "%{$search}%");
            });
        }

        if ($request->filled('min_price')) {
            $query->where('coupon_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('coupon_price', '<=', $request->max_price);
        }

        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('coupon_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('coupon_price', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
                break;
        }

        $properties = $query->paginate(12)->withQueryString();

        $statusCounts = [
            'all' => Property::count(),
            'draft' => Property::where('status', 'draft')->count(),
            'active' => Property::where('status', 'active')->count(),
            'pending_draw' => Property::where('status', 'pending_draw')->count(),
            'completed' => Property::where('status', 'completed')->count(),
            'cancelled' => Property::where('status', 'cancelled')->count(),
        ];

        return view('pages.web.properties.index', compact('properties', 'statusCounts'));
    }
}
