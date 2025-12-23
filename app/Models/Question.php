<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'symptomid',
        'question_text',
        'answers',
        'question_index',
        'is_active',
    ];

    protected $casts = [
        'answers' => 'array',  // Cast answers to an array
    ];

    public function symptom()
    {
        return $this->belongsTo(Symptom::class, 'symptomid');
    }
}
