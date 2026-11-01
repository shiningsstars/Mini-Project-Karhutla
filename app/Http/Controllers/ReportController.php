<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Report::with(['area', 'user']);

        if ($user->role === 'pelapor') {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        $allowedStatuses = [
            'submitted',
            'reviewed',
            'needs_attention',
            'forwarded',
            'completed',
        ];

        if (in_array($request->status, $allowedStatuses, true)) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $areas = Area::orderBy('name')->get();

        return view('reports.index', compact('reports', 'areas'));
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
            'evidence' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
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

    public function edit(Report $report)
    {
        abort_unless(Auth::user()->role === 'pelapor', 403);

        abort_unless($report->user_id === Auth::id(), 403);

        abort_unless($report->status === 'submitted', 403);

        $areas = Area::orderBy('name')->get();

        return view('reports.edit', compact('report', 'areas'));
    }

    public function update(Request $request, Report $report)
    {
        abort_unless(Auth::user()->role === 'pelapor', 403);

        abort_unless($report->user_id === Auth::id(), 403);

        abort_unless($report->status === 'submitted', 403);

        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'incident_at' => 'required|date',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'condition_level' => 'required|in:low,medium,high',
            'evidence' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $oldEvidencePath = $report->evidence_path;
        $newEvidencePath = null;

        if ($request->hasFile('evidence')) {
            $newEvidencePath = $request->file('evidence')
                ->store('report-evidence', 'public');
        }

        $report->update([
            'area_id' => $validated['area_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'incident_at' => $validated['incident_at'],
            'address' => $validated['address'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'condition_level' => $validated['condition_level'],
            'evidence_path' => $newEvidencePath ?? $oldEvidencePath,
        ]);

        if ($newEvidencePath && $oldEvidencePath) {
            Storage::disk('public')->delete($oldEvidencePath);
        }

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'Laporan berhasil diperbarui.');
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

    public function getArea(Area $area)
    {
        return response()->json([
            'id' => $area->id,
            'name' => $area->name,
            'description' => $area->description,
        ]);
    }
}