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
        // NOTE: name/email/mobile/dob/gender were cast as 'encrypted' in commit 17c6b54,
        // but existing patient rows are plaintext (no data migration was run), which threw
        // "DecryptException: The payload is invalid." on every read. Reverted to plaintext.
        // To encrypt PII at rest later, do it deliberately: settle a stable APP_KEY (not the
        // one committed in .env), then migrate existing rows before re-adding these casts.
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
