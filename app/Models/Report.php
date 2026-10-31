<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'area_id',
        'title',
        'description',
        'incident_at',
        'address',
        'latitude',
        'longitude',
        'condition_level',
        'status',
        'evidence_path',
    ];

    protected function casts(): array
    {
        return [
            'incident_at' => 'datetime',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ReportReview::class);
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(ReportFollowUp::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ReportStatusHistory::class);
    }
}
