<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceAssignment extends Model
{
    protected $fillable = ['resource_id', 'request_id', 'assigned_by', 'assigned_at', 'released_at', 'notes'];

    protected function casts(): array
    {
        return ['assigned_at' => 'datetime', 'released_at' => 'datetime'];
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
