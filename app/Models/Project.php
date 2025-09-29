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
        'cityid',
        'stateid',
        'countryid',
        'isactive',
        'startdate',
        'enddate',
        'budget',
        'programid',
        'othercity',
    ];

    protected $casts = [
        'isactive' => 'boolean',
        'startdate' => 'date',
        'enddate' => 'date',
        'budget' => 'double',
    ];

    // Relationships
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
