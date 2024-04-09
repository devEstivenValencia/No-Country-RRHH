<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vacation extends Model
{
    use HasFactory;
    use HasUuids;

    const UPDATED_AT = null;

    protected $fillable = [
        'employee_id',
        'initial_date',
        'final_date',
        'comments',
        'state'
    ];
    protected $casts = [
        'state' => 'string'
    ];
    public function employee(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
