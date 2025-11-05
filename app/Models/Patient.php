<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{

 protected $fillable = [
        'name',
        'filenumber',
        'email',
        'mobile',
        'dob',
        'gender',
        'height',
        'weight',
        'smoke',
        'drinkalcohol',
        'hobbies',
        'reasonvisit',
        'bp',
        'heartrate',
        'temperature',
        'occupation',
        'preferredphysician',
        'oxygensaturation',
        'is_active',
        'profile',
        'existingmedicalcondition',
        'existingmedicalhistory',
        'existingmedication',
        'additionalcomment',
    ];

    public function records()
    {
        return $this->hasMany(Record::class, 'patientid');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'programid');
    }
}

