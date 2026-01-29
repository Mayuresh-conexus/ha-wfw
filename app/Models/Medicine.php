<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'symptom_ids',
        'name',
        'type',
        'dosage',
        'is_active',
    ];

    protected $casts = [
        'symptom_ids' => 'array',
        'is_active' => 'boolean',
    ];

    public function symptom()
    {
        return $this->belongsTo(Symptom::class);
    }
}
