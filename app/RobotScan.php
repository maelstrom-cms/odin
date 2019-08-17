<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RobotScan extends Model
{
    protected $fillable = [
        'txt',
    ];

    protected $hidden = [
        'website_id',
        'updated_at',
    ];
}
