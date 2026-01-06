<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    use HasFactory;

    // Allow mass assignment
    protected $fillable = [
        'name',
        'is_active',
    ];

    // Cast is_active to boolean
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
