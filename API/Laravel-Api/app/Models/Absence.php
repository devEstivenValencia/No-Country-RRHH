<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absence extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'employee_id',
        'date',
        'justification',
    ];

    protected $casts = [];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
