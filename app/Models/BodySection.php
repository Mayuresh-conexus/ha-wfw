<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodySection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'isactive',
        'tag',
        'iscritical',
    ];

    protected $casts = [
        'isactive'   => 'boolean',
        'iscritical' => 'boolean',
    ];
}
