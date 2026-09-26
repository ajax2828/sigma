<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberHistory extends Model
{
    protected $fillable = [
        'member_id',
        'action',
        'initial',
        'name',
        'role',
        'code',
        'description',
        'photo',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
