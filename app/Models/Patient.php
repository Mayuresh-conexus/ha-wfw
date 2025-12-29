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
        'heightunit',
        'weight',
        'weightunit',
        'smoke',
        'drinkalcohol',
        'generalhealth',
        'generalhealthupload',
        'reasonvisit',
        'reasontovisit',
        'bp',
        'heartrate',
        'temperature',
        'occupation',
        'preferredphysician',
        'oxygensaturation',
        'is_active',
        'profile',
        'medication',
        'medicationupload',
        'familyhealthreason',
        'malariatest',
        'malariatestupload',
        'hivtest',
        'hivtestupload',
        'additionalcomment',
        'programid',
    ];

    protected $casts = [
        'smoke' => 'boolean',
        'drinkalcohol' => 'boolean',
        'is_active' => 'boolean',
        // REMOVED array casts — we handle JSON manually
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
