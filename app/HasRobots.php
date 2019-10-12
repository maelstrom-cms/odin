<?php

namespace App;

trait HasRobots
{
    public function robots()
    {
        return $this->hasMany(RobotScan::class);
    }

    public function getLastRobotScansAttribute()
    {
        return $this->robots()->orderBy('created_at', 'desc')->take(2)->get();
    }

    public function getRobotsUrlAttribute()
    {
        return $this->url . '/robots.txt';
    }

}
