<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    public function records()
    {
        return $this->hasMany(Record::class, 'patientid');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'programid');
    }
}

