<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'pelapor') {
            $reports = Report::with('area')
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(10);
        } else {
            $reports = Report::with(['area', 'user'])
                ->latest()
                ->paginate(10);
        }

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        abort_unless(Auth::user()->role === 'pelapor', 403);

        $areas = Area::orderBy('name')->get();

        return view('reports.create', compact('areas'));
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->role === 'pelapor', 403);

        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'incident_at' => 'required|date',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'condition_level' => 'required|in:low,medium,high',
            'evidence' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $evidencePath = $request->file('evidence')
        ->store('report-evidence', 'public');

        Report::create([
            'user_id' => Auth::id(),
            'area_id' => $validated['area_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'incident_at' => $validated['incident_at'],
            'address' => $validated['address'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'condition_level' => $validated['condition_level'],
            'status' => 'submitted',
            'evidence_path' => $evidencePath,
        ]);

        return redirect()
            ->route('reports.index')
            ->with('success', 'Laporan berhasil dikirim.');
    }

    public function show(Report $report)
    {
        $user = Auth::user();

        if ($user->role === 'pelapor') {
            abort_unless($report->user_id === $user->id, 403);
        }

        $report->load(['area', 'user', 'reviews.reviewer', 'followUps.coordinator']);

        return view('reports.show', compact('report'));
    }
}