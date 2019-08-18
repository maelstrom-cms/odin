<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UptimeScan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'was_online' => 'boolean',
    ];

    public function getOfflineAttribute()
    {
        return !$this->was_online;
    }

    public function getOnlineAttribute()
    {
        return $this->was_online;
    }

}
