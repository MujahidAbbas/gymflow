<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the Super Admin dashboard.
     */
    public function index()
    {
        // Ensure only super-admin can access
        abort_unless(auth()->user()->hasRole('super-admin'), 403);

        // Total customers (owners with tenant accounts)
        $totalCustomers = Tenant::count();

        // Active subscriptions
        $activeSubscriptions = User::whereHas('roles', function ($query) {
            $query->where('name', 'owner');
        })->where(function ($query) {
            $query->whereNull('subscription_expire_date')
                ->orWhere('subscription_expire_date', '>', now());
        })->count();

        // Total revenue - Using count of paid subscriptions as placeholder
        // TODO: Integrate with actual payment/invoice system for real revenue
        $totalRevenue = Subscription::where('status', 'active')->count() * 50; // Placeholder calculation

        // Total end users across all customers
        $totalUsers = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['member', 'trainer', 'manager', 'receptionist']);
        })->count();

        // Active trials
        $activeTrials = Tenant::where('trial_ends_at', '>', now())->count();

        // Recent customers (last 7 days)
        $recentCustomers = Tenant::where('created_at', '>=', now()->subDays(7))
            ->with('owner')
            ->latest()
            ->take(10)
            ->get();

        // Customer growth data for chart (last 12 months)
        $customerGrowth = Tenant::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Revenue trends for chart (last 12 months) - Using subscription count as placeholder
        $revenueTrends = Subscription::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as total')
        )
            ->where('status', 'active')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Subscription status breakdown
        $subscriptionBreakdown = [
            'active' => Tenant::where('status', 'active')->count(),
            'trial' => Tenant::where('trial_ends_at', '>', now())->count(),
            'suspended' => Tenant::where('status', 'suspended')->count(),
            'inactive' => Tenant::where('status', 'inactive')->count(),
        ];

        return view('super-admin.dashboard.index', compact(
            'totalCustomers',
            'activeSubscriptions',
            'totalRevenue',
            'totalUsers',
            'activeTrials',
            'recentCustomers',
            'customerGrowth',
            'revenueTrends',
            'subscriptionBreakdown'
        ));
    }
}
