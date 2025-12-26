<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSubscriptionTier;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /**
     * Display analytics overview dashboard.
     */
    public function index(): View
    {
        abort_unless(auth()->user()->hasRole('super-admin'), 403);

        $metrics = [
            'total_customers' => Tenant::count(),
            'active_customers' => Tenant::where('status', 'active')->count(),
            'customers_on_trial' => Tenant::whereNotNull('trial_ends_at')
                ->where('trial_ends_at', '>', now())
                ->count(),
            'total_revenue' => $this->calculateTotalRevenue(),
            'mrr' => $this->calculateMRR(),
            'customer_growth_rate' => $this->calculateCustomerGrowthRate(),
        ];

        return view('super-admin.analytics.index', compact('metrics'));
    }

    /**
     * Display revenue analytics.
     */
    public function revenue(): View
    {
        abort_unless(auth()->user()->hasRole('super-admin'), 403);

        $revenueData = $this->getRevenueData();
        $mrr = $this->calculateMRR();
        $arr = $mrr * 12;

        return view('super-admin.analytics.revenue', compact('revenueData', 'mrr', 'arr'));
    }

    /**
     * Display customer analytics.
     */
    public function customers(): View
    {
        abort_unless(auth()->user()->hasRole('super-admin'), 403);

        $customerData = $this->getCustomerGrowthData();
        $churnRate = $this->calculateChurnRate();
        $customersByStatus = Tenant::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('super-admin.analytics.customers', compact('customerData', 'churnRate', 'customersByStatus'));
    }

    /**
     * Display subscription analytics.
     */
    public function subscriptions(): View
    {
        abort_unless(auth()->user()->hasRole('super-admin'), 403);

        $tierDistribution = PlatformSubscriptionTier::withCount('tenants')
            ->get()
            ->map(function ($tier) {
                return [
                    'name' => $tier->name,
                    'count' => $tier->tenants_count,
                    'percentage' => Tenant::count() > 0 
                        ? round(($tier->tenants_count / Tenant::count()) * 100, 1)
                        : 0,
                ];
            });

        return view('super-admin.analytics.subscriptions', compact('tierDistribution'));
    }

    /**
     * Calculate Monthly Recurring Revenue (MRR).
     */
    protected function calculateMRR(): float
    {
        $mrr = 0;

        // Calculate based on active tenants with subscription tiers
        $tenants = Tenant::where('status', 'active')
            ->whereNotNull('platform_subscription_tier_id')
            ->with('platformSubscriptionTier')
            ->get();

        foreach ($tenants as $tenant) {
            if ($tenant->platformSubscriptionTier) {
                $price = $tenant->platformSubscriptionTier->price;
                $interval = $tenant->platformSubscriptionTier->interval;

                // Normalize to monthly
                $monthlyPrice = match($interval) {
                    'monthly' => $price,
                    'quarterly' => $price / 3,
                    'yearly' => $price / 12,
                    'lifetime' => 0, // Lifetime doesn't contribute to MRR
                    default => 0,
                };

                $mrr += $monthlyPrice;
            }
        }

        return round($mrr, 2);
    }

    /**
     * Calculate total revenue (simplified placeholder).
     */
    protected function calculateTotalRevenue(): float
    {
        // This would come from actual payment transactions
        // For now, estimate based on MRR * months in operation
        return $this->calculateMRR() * 12;
    }

    /**
     * Calculate customer growth rate.
     */
    protected function calculateCustomerGrowthRate(): float
    {
        $currentMonth = Tenant::whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])->count();

        $lastMonth = Tenant::whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth()
        ])->count();

        if ($lastMonth == 0) {
            return $currentMonth > 0 ? 100 : 0;
        }

        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    /**
     * Calculate churn rate.
     */
    protected function calculateChurnRate(): float
    {
        $startOfMonth = Tenant::where('created_at', '<', now()->startOfMonth())->count();
        
        $churned = Tenant::where('status', 'suspended')
            ->whereBetween('updated_at', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ])->count();

        if ($startOfMonth == 0) {
            return 0;
        }

        return round(($churned / $startOfMonth) * 100, 1);
    }

    /**
     * Get revenue data for charts (last 12 months).
     */
    protected function getRevenueData(): array
    {
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            
            // Count active customers in that month
            $activeCustomers = Tenant::where('status', 'active')
                ->where('created_at', '<=', $date->endOfMonth())
                ->whereNotNull('platform_subscription_tier_id')
                ->with('platformSubscriptionTier')
                ->get();

            $revenue = 0;
            foreach ($activeCustomers as $customer) {
                if ($customer->platformSubscriptionTier) {
                    $price = $customer->platformSubscriptionTier->price;
                    $interval = $customer->platformSubscriptionTier->interval;

                    $monthlyPrice = match($interval) {
                        'monthly' => $price,
                        'quarterly' => $price / 3,
                        'yearly' => $price / 12,
                        default => 0,
                    };

                    $revenue += $monthlyPrice;
                }
            }

            $data[] = [
                'month' => $date->format('M Y'),
                'revenue' => round($revenue, 2),
            ];
        }

        return $data;
    }

    /**
     * Get customer growth data (last 12 months).
     */
    protected function getCustomerGrowthData(): array
    {
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            
            $newCustomers = Tenant::whereBetween('created_at', [
                $date->startOfMonth()->copy(),
                $date->endOfMonth()->copy()
            ])->count();

            $totalCustomers = Tenant::where('created_at', '<=', $date->endOfMonth())->count();

            $data[] = [
                'month' => $date->format('M Y'),
                'new' => $newCustomers,
                'total' => $totalCustomers,
            ];
        }

        return $data;
    }
}
