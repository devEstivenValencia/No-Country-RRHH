<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;
    use HasUuids;

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'company_id',
        'name',
        'id_legal',
        'employee_code',
        'address',
        'contact',
        'admission_date',
        'finish_date',
        'state',
        'role'
    ];

    protected $casts = [
        'contact' => 'array',
        'role' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
