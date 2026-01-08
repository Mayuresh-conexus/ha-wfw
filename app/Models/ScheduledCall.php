<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledCall extends Model
{
    use HasFactory;

    protected $table = 'scheduled_calls';

    protected $fillable = [
        'patientid',
        'recordid',
        'volunteer_id',
        'assigned_gp_doctor_id',
        'schedule_date',
        'schedule_start_time',
        'schedule_end_time',
        'room_name',
        'zoom_meeting_id',
        'zoom_join_url',
        'zoom_start_url',
        'status',
    ];


    public function record()
    {
        return $this->belongsTo(Record::class, 'recordid');
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'assigned_gp_doctor_id');
    }
    public function assignedDoctor()
    {
        return $this->belongsTo(User::class, 'assigned_gp_doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patientid');
    }

}
