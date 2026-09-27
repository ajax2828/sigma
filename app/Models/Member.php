<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'initial',
        'name',
        'role',
        'code',
        'description',
        'motto',
        'photo',
        'sort_order',
    ];
}
