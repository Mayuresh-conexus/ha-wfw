<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{


    protected $fillable = [
    'patientid',
    'doctorid',
    'gpid',
    'volunteerid',
    'projectid',
    'programid',
    'record_type',
    'notes',
    'attachments',
    'status',
    'submitted_at',
];

protected $casts = [
    'attachments' => 'array',
    'submitted_at' => 'datetime',
];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patientid');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctorid');
    }

     public function gp()
    {
        return $this->belongsTo(User::class, 'gpid');
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'volunteerid');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'projectid');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'programid');
    }

    public function diagnoses()
    {
        return $this->hasMany(RecordDiagnosis::class, 'recordid');
    }

    public function comments()
    {
        return $this->hasMany(RecordComment::class, 'recordid');
    }

    public function prescriptions()
    {
        return $this->hasMany(RecordPrescription::class, 'recordid');
    }

    public function calls()
{
    return $this->hasMany(ScheduledCall::class, 'recordid');
}
}
