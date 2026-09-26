<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'icon',
        'title',
        'year',
        'description',
        'sort_order',
    ];
}
