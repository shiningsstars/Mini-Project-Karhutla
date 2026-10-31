<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportFollowUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportFollowUpController extends Controller
{
    public function store(Request $request, Report $report)
    {
        abort_unless(Auth::user()->role === 'koordinator', 403);
        abort_unless($report->status === 'needs_attention', 403);
        abort_if($report->followUps()->exists(), 403);

        $validated = $request->validate([
            'destination' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'forwarded_at' => 'required|date',
        ]);

        DB::transaction(function () use ($validated, $report) {
            ReportFollowUp::create([
                'report_id' => $report->id,
                'coordinator_id' => Auth::id(),
                'destination' => $validated['destination'],
                'notes' => $validated['notes'] ?? null,
                'forwarded_at' => $validated['forwarded_at'],
            ]);

            $report->update([
                'status' => 'forwarded',
            ]);
        });

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'Informasi laporan berhasil dicatat sebagai diteruskan.');
    }
}
