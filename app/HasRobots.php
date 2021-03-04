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
        parts = parse_url($this->url);
        return sprintf('%s://%s/robots.txt', $parts['scheme'], $parts['host']);
    }

}
