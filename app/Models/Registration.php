<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'study', 'Institute', 'user_status',
        'unique_code', 'qr_code_path',
        'is_scanned', 'scanned_at',
    ];

    protected $casts = [
        'is_scanned' => 'boolean',
        'scanned_at' => 'datetime',
    ];
}
