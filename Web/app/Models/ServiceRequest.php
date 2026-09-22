<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceRequest extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUSES = ['PENDIENTE', 'ASIGNADA', 'EN_PROCESO', 'RESUELTA', 'CERRADA'];

    public const PRIORITIES = ['BAJA', 'MEDIA', 'ALTA'];

    protected $table = 'requests';

    protected $fillable = [
        'tracking_code', 'user_id', 'request_type_id', 'assigned_to', 'title',
        'description', 'priority', 'status', 'location', 'closed_at',
    ];

    protected function casts(): array
    {
        return ['closed_at' => 'datetime'];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(RequestType::class, 'request_type_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(RequestEvidence::class, 'request_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(RequestComment::class, 'request_id');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(RequestStatusHistory::class, 'request_id')->latest();
    }

    public function resourceAssignments(): HasMany
    {
        return $this->hasMany(ResourceAssignment::class, 'request_id');
    }
}
