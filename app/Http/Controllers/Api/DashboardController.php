<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LoginLog;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     * This endpoint does not require any permissions, only authentication
     */
    public function index(Request $request)
    {
        // Basic stats
        $servicesCount = Service::count();
        $productsCount = Product::count();
        $newLeadsCount = Lead::where('status', 'new')->count();

        // Recent leads (last 10)
        $recentLeads = Lead::orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($lead) {
                return [
                    'id' => $lead->id,
                    'name' => $lead->name,
                    'email' => $lead->email,
                    'type' => $lead->type,
                    'status' => $lead->status,
                    'is_read' => $lead->is_read,
                    'created_at' => $lead->created_at->toISOString(),
                ];
            });

        // Login statistics
        $loginStats = $this->getLoginStatistics();

        return response()->json([
            'stats' => [
                'services' => $servicesCount,
                'products' => $productsCount,
                'leads' => $newLeadsCount,
            ],
            'recent_leads' => $recentLeads,
            'login_stats' => $loginStats,
        ]);
    }

    /**
     * Get login statistics
     */
    private function getLoginStatistics()
    {
        $totalLogs = LoginLog::count();
        $successfulLogins = LoginLog::where('status', 'success')->count();
        $failedLogins = LoginLog::where('status', 'failed')->count();

        return [
            'total' => $totalLogs,
            'successful' => $successfulLogins,
            'failed' => $failedLogins,
        ];
    }
}

