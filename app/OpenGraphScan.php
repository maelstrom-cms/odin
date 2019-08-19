<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpenGraphScan extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'created_at', 'id',
        'updated_at', 'website_id',
    ];
}
