<?php

namespace App\Http\Controllers\Be\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Property;
use App\Models\Raffle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    /**
     * Display a listing of coupons.
     */
    public function index(Request $request)
    {
        $query = Coupon::with(['buyer', 'property', 'order']);

        // Filter by property
        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        // Filter by winner status
        if ($request->filled('is_winner')) {
            $query->where('is_winner', $request->is_winner);
        }

        // Search by coupon number or buyer name
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('coupon_number', 'like', '%' . $request->search . '%')
                    ->orWhereHas('buyer', function ($sq) use ($request) {
                        $sq->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $coupons = $query->orderBy('created_at', 'desc')->paginate(15);
        $properties = Property::where('status', 'active')->get();

        // Calculate statistics for cards
        $totalCoupons = Coupon::count();
        $totalWinners = Coupon::where('is_winner', true)->count();
        $totalProperties = Property::count();
        $activeRaffles = Property::whereHas('coupons')->doesntHave('raffles')->count();

        return view('pages.be.admin.coupons.index', compact(
            'coupons',
            'properties',
            'totalCoupons',
            'totalWinners',
            'totalProperties',
            'activeRaffles'
        ));
    }

    /**
     * Display the specified coupon.
     */
    public function show(Coupon $coupon)
    {
        $coupon->load(['buyer', 'property', 'order']);
        return view('pages.be.admin.coupons.show', compact('coupon'));
    }

    /**
     * Show raffle management page.
     */
    public function raffles(Request $request)
    {
        $query = Property::with(['coupons', 'raffles']);

        // Only show properties that have coupons
        $query->whereHas('coupons');

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->whereDoesntHave('raffles');
            } elseif ($request->status === 'completed') {
                $query->whereHas('raffles', function ($q) {
                    $q->where('status', 'completed');
                });
            }
        }

        $properties = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('pages.be.admin.coupons.raffles', compact('properties'));
    }

    /**
     * Show raffle detail for a property.
     */
    public function raffleDetail(Property $property)
    {
        $property->load(['coupons.buyer', 'raffles']);
        $coupons = $property->coupons()->with('buyer')->get();

        return view('pages.be.admin.coupons.raffle-detail', compact('property', 'coupons'));
    }

    /**
     * Conduct raffle for a property.
     */
    public function conductRaffle(Request $request, Property $property)
    {
        // SECURITY: Authorization check using Policy
        if (!Gate::allows('conductRaffle', $property)) {
            Log::warning('Unauthorized raffle attempt', [
                'user_id' => Auth::id(),
                'property_id' => $property->id,
                'property_title' => $property->title,
                'status' => $property->status,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            return redirect()->route('admin.coupons.raffle-detail', $property)
                ->with('error', 'Anda tidak memiliki izin untuk melakukan pengundian pada properti ini.');
        }

        // SECURITY: Log raffle attempt
        Log::info('Raffle conduct attempt initiated', [
            'user_id' => Auth::id(),
            'property_id' => $property->id,
            'property_title' => $property->title,
            'status' => $property->status,
            'coupons_count' => $property->coupons()->count()
        ]);

        // SECURITY: Validate request input
        $request->validate([
            'draw_date' => 'nullable|date|before_or_equal:now',
            'notes' => 'nullable|string|max:1000'
        ]);

        // SECURITY: Additional status check (redundant but safer)
        if ($property->status !== 'active') {
            return redirect()->route('admin.coupons.raffle-detail', $property)
                ->with('error', 'Pengundian hanya dapat dilakukan pada properti yang aktif. Status saat ini: ' . ucfirst($property->status));
        }

        // Check if raffle already exists
        if ($property->raffles()->exists()) {
            return redirect()->route('admin.coupons.raffle-detail', $property)
                ->with('error', 'Pengundian untuk properti ini sudah pernah dilakukan.');
        }

        // Get all coupons for this property
        $coupons = $property->coupons()->get();

        if ($coupons->count() === 0) {
            return redirect()->route('admin.coupons.raffle-detail', $property)
                ->with('error', 'Tidak ada kupon untuk properti ini.');
        }

        DB::beginTransaction();
        try {
            // Select random winner
            $winnerCoupon = $coupons->random();

            // Mark winner
            $winnerCoupon->update(['is_winner' => true]);

            // Create raffle record
            $raffle = Raffle::create([
                'property_id' => $property->id,
                'draw_date' => now(),
                'winning_coupon_id' => $winnerCoupon->id,
                'drawn_by_user_id' => Auth::id(),
                'status' => 'completed',
                'notes' => "Pengundian dilakukan pada " . now() . " dengan total {$coupons->count()} kupon. Pemenang: {$winnerCoupon->buyer->name} (Kupon: {$winnerCoupon->coupon_number})",
            ]);

            // Update property status to completed after raffle
            $property->update(['status' => 'completed']);

            DB::commit();

            // SECURITY: Log successful raffle
            Log::info('Raffle conducted successfully', [
                'user_id' => Auth::id(),
                'property_id' => $property->id,
                'property_title' => $property->title,
                'winner_user_id' => $winnerCoupon->buyer->id,
                'winner_name' => $winnerCoupon->buyer->name,
                'winning_coupon_id' => $winnerCoupon->id,
                'winning_coupon_number' => $winnerCoupon->coupon_number,
                'total_participants' => $coupons->count(),
                'raffle_id' => $raffle->id
            ]);

            return redirect()->route('admin.coupons.raffle-detail', $property)
                ->with('success', "Pengundian berhasil! Pemenang: {$winnerCoupon->buyer->name} dengan kupon {$winnerCoupon->coupon_number}");
        } catch (\Exception $e) {
            DB::rollback();

            // SECURITY: Log raffle error
            Log::error('Raffle conduct failed', [
                'user_id' => Auth::id(),
                'property_id' => $property->id,
                'property_title' => $property->title,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.coupons.raffle-detail', $property)
                ->with('error', 'Terjadi error saat melakukan pengundian: ' . $e->getMessage());
        }
    }

    /**
     * Generate coupon report.
     */
    public function report(Request $request)
    {
        // Get all properties
        $properties = Property::all();

        // Build coupon query
        $couponQuery = Coupon::with(['buyer', 'property', 'order']);

        // Apply date filters if provided
        if ($request->filled('date_from')) {
            $couponQuery->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $couponQuery->whereDate('created_at', '<=', $request->date_to);
        }

        // Apply property filter if provided
        if ($request->filled('property_id')) {
            $couponQuery->where('property_id', $request->property_id);
        }

        $coupons = $couponQuery->get();

        // Calculate statistics
        $totalCoupons = $coupons->count();
        $totalRevenue = $coupons->sum(function ($coupon) {
            return $coupon->property->coupon_price;
        });
        $activeProperties = Property::where('status', 'active')->count();
        $totalWinners = $coupons->where('is_winner', true)->count();

        // Sales by property - apply same filters as coupons
        $salesQuery = Property::with('raffles');

        // Apply property filter if provided
        if ($request->filled('property_id')) {
            $salesQuery->where('id', $request->property_id);
        }

        // Count coupons with date/property filters
        $salesQuery->withCount(['coupons' => function ($query) use ($request) {
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
        }]);

        $salesByProperty = $salesQuery->get()
            ->map(function ($property) {
                return (object) [
                    'title' => $property->title,
                    'city' => $property->city,
                    'province' => $property->province,
                    'coupon_price' => $property->coupon_price,
                    'coupons_count' => $property->coupons_count,
                    'total_revenue' => $property->coupons_count * $property->coupon_price,
                    'raffles_count' => $property->raffles->count(),
                ];
            })
            ->sortByDesc('coupons_count');

        // Top buyers - apply same filters
        $topBuyersQuery = DB::table('coupons')
            ->join('users', 'coupons.buyer_id', '=', 'users.id')
            ->join('properties', 'coupons.property_id', '=', 'properties.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COUNT(coupons.id) as coupons_count'),
                DB::raw('SUM(properties.coupon_price) as total_spent')
            );

        // Apply date filters
        if ($request->filled('date_from')) {
            $topBuyersQuery->whereDate('coupons.created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $topBuyersQuery->whereDate('coupons.created_at', '<=', $request->date_to);
        }

        // Apply property filter
        if ($request->filled('property_id')) {
            $topBuyersQuery->where('coupons.property_id', $request->property_id);
        }

        $topBuyers = $topBuyersQuery
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('coupons_count')
            ->limit(10)
            ->get();

        // Monthly trends - apply same filters
        $monthlyTrendsQuery = DB::table('coupons')
            ->join('properties', 'coupons.property_id', '=', 'properties.id')
            ->select(
                DB::raw('YEAR(coupons.created_at) as year'),
                DB::raw('MONTH(coupons.created_at) as month'),
                DB::raw('MONTHNAME(coupons.created_at) as month_name'),
                DB::raw('COUNT(coupons.id) as coupons_count'),
                DB::raw('SUM(properties.coupon_price) as total_revenue')
            );

        // Apply date filters
        if ($request->filled('date_from')) {
            $monthlyTrendsQuery->whereDate('coupons.created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $monthlyTrendsQuery->whereDate('coupons.created_at', '<=', $request->date_to);
        }

        // Apply property filter
        if ($request->filled('property_id')) {
            $monthlyTrendsQuery->where('coupons.property_id', $request->property_id);
        }

        $monthlyTrends = $monthlyTrendsQuery
            ->groupBy('year', 'month', 'month_name')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->limit(12)
            ->get();

        // Recent sales - apply same filters
        $recentSalesQuery = Coupon::with(['buyer', 'property']);

        // Apply date filters
        if ($request->filled('date_from')) {
            $recentSalesQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $recentSalesQuery->whereDate('created_at', '<=', $request->date_to);
        }

        // Apply property filter
        if ($request->filled('property_id')) {
            $recentSalesQuery->where('property_id', $request->property_id);
        }

        $recentSales = $recentSalesQuery
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('pages.be.admin.coupons.report', compact(
            'properties',
            'totalCoupons',
            'totalRevenue',
            'activeProperties',
            'totalWinners',
            'salesByProperty',
            'topBuyers',
            'monthlyTrends',
            'recentSales'
        ));
    }
}
