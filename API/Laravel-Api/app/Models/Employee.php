<?php

namespace App\Models;

use App\Classes\CustomEncrypter;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'id_legal' => 'encrypted',
        'address' => 'encrypted',
        'contact' => 'array',
        'role' => 'array'
    ];

    protected $hidden = [
        'id',
        'user_id',
        'created_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function contact(): Attribute
    {
        return new Attribute(
            get: fn ($value) => CustomEncrypter::decryptDB($value),
            set: fn ($value) => json_encode(CustomEncrypter::encryptDB($value))
        );
    }
}
