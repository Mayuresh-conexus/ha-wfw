<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'gpid',
        'volunteerid',
        'cityid',
        'stateid',
        'countryid',
        'is_active',
        'startdate',
        'enddate',
        'budget',
        'programid',
        'othercity',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'startdate' => 'date',
        'enddate' => 'date',
        'budget' => 'double',
        'gpid' => 'array',
    ];

    protected $appends = ['gps'];

    // Relationships

    public function getGpsAttribute()
    {
        $ids = $this->gpid ?? [];
        return \App\Models\User::whereIn('id', $ids)->get();
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'volunteerid');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'cityid');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'stateid');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'countryid');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'programid');
    }
}
