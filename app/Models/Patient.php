<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Patient extends Model
{
    use LogsActivity, SoftDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
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
        'name' => 'encrypted',
        'email' => 'encrypted',
        'mobile' => 'encrypted',
        'dob' => 'encrypted',
        'gender' => 'encrypted',
        'smoke' => 'boolean',
        'drinkalcohol' => 'boolean',
        'is_active' => 'boolean',
        'generalhealthupload' => 'array',
        'medicationupload' => 'array',
        'malariatestupload' => 'array',
        'hivtestupload' => 'array',
        // REMOVED array casts — we handle JSON manually
    ];

    public function records()
    {
        return $this->hasMany(Record::class , 'patientid');
    }

    public function program()
    {
        return $this->belongsTo(Program::class , 'programid');
    }
}
