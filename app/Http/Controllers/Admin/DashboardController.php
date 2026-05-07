<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Result;
use App\Models\Test;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats
        $stats = [
            'tests_today'    => Invoice::whereDate('created_at', today())
                                    ->withCount('tests')->get()
                                    ->sum('tests_count'),
            'patients_today' => Patient::whereDate('created_at', today())->count(),
            'revenue_today'  => Invoice::whereDate('created_at', today())->sum('total_amount'),
            'pending'        => Result::where('status', 'submitted')->count(),
        ];

        // Chart 1 — tests per day last 7 days
        $weeklyTests = collect(range(6, 0))->map(function ($days) {
            $date = now()->subDays($days);
            return [
                'label' => $date->format('D'),
                'count' => Invoice::whereDate('created_at', $date)
                               ->withCount('tests')->get()->sum('tests_count'),
            ];
        });

        // Chart 2 — tests by category
        $byCategory = Test::active()
            ->join('invoice_tests', 'tests.id', '=', 'invoice_tests.test_id')
            ->select('tests.category', DB::raw('count(*) as total'))
            ->groupBy('tests.category')
            ->orderByDesc('total')
            ->get();

        // Recent activity
        $activity = Result::with(['invoice.patient', 'biologist', 'doctor', 'invoice'])
            ->latest()->take(8)->get();

        // Staff status
        $staff = User::where('role', '!=', 'admin')->get()->map(function ($u) {
            $u->online_status = $u->isOnline() ? 'online' :
                ($u->last_seen && $u->last_seen->diffInMinutes(now()) < 30 ? 'busy' : 'offline');
            return $u;
        });

        return view('admin.dashboard', compact(
            'stats', 'weeklyTests', 'byCategory', 'activity', 'staff'
        ));
    }
}