<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodySection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
        'tag',
        'iscritical',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'iscritical' => 'boolean',
    ];

    public function symptoms()
        {
            return $this->hasMany(Symptom::class, 'body_section_id');
        }


}
