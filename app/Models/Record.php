<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Record extends Model
{
    use LogsActivity, SoftDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }


    protected $fillable = [
        'patientid',
        'doctorid',
        'gpid',
        'volunteerid',
        'projectid',
        'programid',
        'medicineid',
        'symptom_ids',
        'question_summary',
        'record_type',
        'notes',
        'attachments',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'doctorid' => 'array',
        'gpid' => 'array',
        'attachments' => 'array',
        'symptom_ids' => 'array', // ← ADD THIS
        'question_summary' => 'array', // ← ADD THIS (for consistency)
        'submitted_at' => 'datetime',
        'medicineid' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class , 'patientid');
    }

    public function getDoctorsAttribute()
    {
        $ids = $this->doctorid ?? [];
        return \App\Models\User::whereIn('id', $ids)->get();
    }

    public function getGpsAttribute()
    {
        $ids = $this->gpid ?? [];
        return \App\Models\User::whereIn('id', $ids)->get();
    }


    public function volunteer()
    {
        return $this->belongsTo(User::class , 'volunteerid');
    }

    public function project()
    {
        return $this->belongsTo(Project::class , 'projectid');
    }

    public function program()
    {
        return $this->belongsTo(Program::class , 'programid');
    }

    public function diagnoses()
    {
        return $this->hasMany(RecordDiagnosis::class , 'recordid');
    }

    public function comments()
    {
        return $this->hasMany(RecordComment::class , 'recordid');
    }

    public function prescriptions()
    {
        return $this->hasMany(RecordPrescription::class , 'recordid');
    }

    public function calls()
    {
        return $this->hasMany(ScheduledCall::class , 'recordid');
    }
    public function scheduledCall()
    {
        return $this->hasOne(ScheduledCall::class , 'recordid');
    }

    public function assignedDoctor()
    {
        return $this->belongsTo(User::class , 'assigned_gp_doctor_id');
    }


}
