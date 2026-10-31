<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportReviewController extends Controller
{
    public function store(Request $request, Report $report)
    {
        abort_unless(Auth::user()->role === 'relawan', 403);
        abort_unless($report->status === 'submitted', 403);

        $validated = $request->validate([
            'notes' => 'required|string',
            'review_result' => 'required|in:valid,needs_attention',
        ]);

        DB::transaction(function () use ($validated, $report) {
            ReportReview::create([
                'report_id' => $report->id,
                'reviewer_id' => Auth::id(),
                'notes' => $validated['notes'],
                'review_result' => $validated['review_result'],
            ]);

            $report->update([
                'status' => $validated['review_result'] === 'valid'
                    ? 'reviewed'
                    : 'needs_attention',
            ]);
        });

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'Peninjauan laporan berhasil disimpan.');
    }
}