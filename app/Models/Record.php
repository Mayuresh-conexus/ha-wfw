<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Record extends Model
{
    use LogsActivity, SoftDeletes;

    /**
     * Clinical/patient statuses the care team can assign after auditing a
     * record. Single source of truth shared by the admin form and the API.
     * Stored as the label string in `records.patient_status`.
     */
    public const PATIENT_STATUSES = [
        'Normal',
        'Critical',
        'Medicine Prescribed',
        'Required Specialist Doctor',
        'No Treatment Required',
        'Required Additional Reports',
        'Video call Scheduled',
        'Treatment Complete',
    ];

    /** value => label map for Filament / <select> options. */
    public static function patientStatusOptions(): array
    {
        return array_combine(self::PATIENT_STATUSES, self::PATIENT_STATUSES);
    }

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
        'patient_status',
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
