<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     * This endpoint does not require any permissions, only authentication
     */
    public function index(Request $request)
    {
        // Stats from existing models only (Service, Product, Lead models may not exist in this app)
        $servicesCount = 0;
        $productsCount = 0;
        $newLeadsCount = 0;
        $recentLeads = [];

        if (file_exists(app_path('Models/Service.php'))) {
            $servicesCount = \App\Models\Service::count();
        }
        if (file_exists(app_path('Models/Product.php'))) {
            $productsCount = \App\Models\Product::count();
        }
        if (file_exists(app_path('Models/Lead.php'))) {
            $newLeadsCount = \App\Models\Lead::where('status', 'new')->count();
            $recentLeads = \App\Models\Lead::orderBy('created_at', 'desc')
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
                })
                ->all();
        }

        $loginStats = $this->getLoginStatistics();

        return response()->json([
            'stats' => [
                'services' => $servicesCount,
                'products' => $productsCount,
                'leads' => $newLeadsCount,
                'users' => User::count(),
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

