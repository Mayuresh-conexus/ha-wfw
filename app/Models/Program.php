<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{

     protected $fillable = [
        'name',
        'description',
        'isactive',
    ];

    // Cast isactive to boolean automatically
    protected $casts = [
        'isactive' => 'boolean',
    ];

    public function patients()
    {
        return $this->hasMany(Patient::class, 'programid');
    }

    public function records()
    {
        return $this->hasMany(Record::class, 'programid');
    }
}
