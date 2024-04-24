<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KeyManager extends Model
{
    use HasFactory;
    use HasUuids;
    public $timestamps = false;

    protected $fillable = [
        'key',
        'expires_at'
    ];
}
