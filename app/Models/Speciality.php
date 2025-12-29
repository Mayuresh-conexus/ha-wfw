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
        'isactive',
    ];

    // Cast isactive to boolean
    protected $casts = [
        'isactive' => 'boolean',
    ];
}
