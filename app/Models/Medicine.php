<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'symptom_id',
        'name',
        'type',
        'dosage',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function symptom()
    {
        return $this->belongsTo(Symptom::class);
    }
}
