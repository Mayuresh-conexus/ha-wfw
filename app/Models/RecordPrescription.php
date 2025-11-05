<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordPrescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'recordid',
        'medicine_name',
        'dosage',
        'frequency',
        'instructions',
    ];

    public function record()
    {
        return $this->belongsTo(Record::class, 'recordid');
    }
}
