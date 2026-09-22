<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use SoftDeletes;

    public const STATUSES = ['DISPONIBLE', 'ASIGNADO', 'MANTENIMIENTO', 'BAJA'];

    protected $fillable = ['code', 'name', 'category', 'status', 'location', 'description'];

    public function assignments(): HasMany
    {
        return $this->hasMany(ResourceAssignment::class);
    }
}
