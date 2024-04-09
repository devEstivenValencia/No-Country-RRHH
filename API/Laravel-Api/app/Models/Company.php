<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;
    use HasUuids;

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'company_name',
        'working_week',
        'roles',
        'verified',
        'number_of_employees',
        'sector',
        'location',
        'contact'
    ];

    protected $casts = [
        'working_week' => 'array',
        'roles' => 'array',
        'location' => 'array',
        'contact' => 'array'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function nonWorkingDays(): HasMany
    {
        return $this->hasMany(NonWorkingDays::class);
    }
}
