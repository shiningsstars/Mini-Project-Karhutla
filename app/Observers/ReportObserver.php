<?php

namespace App\Observers;

use App\Models\Report;
use App\Models\ReportStatusHistory;
use Illuminate\Support\Facades\Auth;

class ReportObserver
{
    public function updated(Report $report): void
    {
        if ($report->wasChanged('status')) {
            ReportStatusHistory::create([
                'report_id' => $report->id,
                'changed_by' => Auth::id(),
                'old_status' => $report->getOriginal('status'),
                'new_status' => $report->status,
            ]);
        }
    }
}