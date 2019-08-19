<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DnsScan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'records' => 'array',
    ];

    protected $hidden = [
        'website_id', 'records',
        'updated_at', 'diff',
    ];
}
