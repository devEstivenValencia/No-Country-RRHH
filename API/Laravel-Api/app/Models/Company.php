<?php

namespace App\Models;

use App\Classes\CustomEncrypter;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'contact' => 'array',
        'verified' => 'boolean'
    ];

    protected $hidden = [
        // 'id',
        'user_id',
        'created_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function nonWorkingDays(): HasMany
    {
        return $this->hasMany(NonWorkingDays::class);
    }
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    protected function contact(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => CustomEncrypter::decryptDB($value),
            set: fn ($value) => json_encode(CustomEncrypter::encryptDB($value))
        );
    }

    protected function location(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => CustomEncrypter::decryptDB($value),
            set: fn ($value) => json_encode(CustomEncrypter::encryptDB($value))
        );
    }
}
