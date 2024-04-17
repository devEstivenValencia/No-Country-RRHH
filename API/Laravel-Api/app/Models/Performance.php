<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Performance extends Model
{
    use HasFactory;
    use HasUuids;
    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'rating'
    ];
    protected $casts = [];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
