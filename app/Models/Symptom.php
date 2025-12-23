<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'body_section_id',
        'isactive',
        'tag',
        'iscritical',
    ];

    protected $casts = [
        'isactive'   => 'boolean',
        'iscritical' => 'boolean',
    ];

    public function bodysection()
{
    return $this->belongsTo(BodySection::class, 'body_section_id');
}

 public function questions()
        {
            return $this->hasMany(Question::class, 'symptomid');
        }
}
