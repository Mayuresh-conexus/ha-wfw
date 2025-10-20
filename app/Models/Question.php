<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'is_active',
        'questiontype',
        'tags'
    ];

    protected $casts = [
        'is_active'   => 'boolean',
    ];
    

}
