<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'userid',
        'recordid',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }

    public function record()
    {
        return $this->belongsTo(Record::class, 'recordid');
    }
}
