<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportRecapController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Auth::user()->role === 'koordinator', 403);

        $request->validate([
            'area_id' => 'nullable|exists:areas,id',
        ]);

        $activeStatuses = [
            'submitted',
            'reviewed',
            'needs_attention',
        ];

        $reportQuery = Report::query();

        if ($request->filled('area_id')) {
            $reportQuery->where('area_id', $request->area_id);
        }

        $totalReports = (clone $reportQuery)->count();

        $activeReports = (clone $reportQuery)
            ->whereIn('status', $activeStatuses)
            ->count();

        $forwardedReports = (clone $reportQuery)
            ->where('status', 'forwarded')
            ->count();

        $recaps = Area::withCount([
            'reports as total_reports',

            'reports as active_reports' => function ($query) use ($activeStatuses) {
                $query->whereIn('status', $activeStatuses);
            },

            'reports as forwarded_reports' => function ($query) {
                $query->where('status', 'forwarded');
            },
        ])
        ->when($request->filled('area_id'), function ($query) use ($request) {
            $query->where('id', $request->area_id);
        })
        ->orderBy('name')
        ->get()
        ->filter(function ($area) {
            return $area->total_reports > 0;
        });

        $areas = Area::orderBy('name')->get();

        return view('reports.recap', compact(
            'totalReports',
            'activeReports',
            'forwardedReports',
            'recaps',
            'areas'
        ));
    }
}