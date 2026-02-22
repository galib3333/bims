<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $table = 'incidents';
    protected $fillable = [
        'title',
        'description',
        'latitude',
        'longitude',
        'severity',
        'category',
        'division',
        'district',
        'occurred_at'
    ];
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'severity' => 'integer',
        'occurred_at' => 'datetime',
    ];
}
