<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = [
        'url',
        'user_id',
        'ssl_enabled',
        'uptime_enabled',
        'robots_enabled',
        'dns_enabled',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function robots()
    {
        return $this->hasMany(RobotScan::class);
    }

    public function getLastRobotScansAttribute()
    {
        return $this->robots()->orderBy('created_at', 'desc')->take(2)->get();

        if ($last->isEmpty()) {
            return collect();
        }

        if ($last->count() === 1) {
            return collect([
                $last->first(),
            ]);
        }

        return collect([
            $last->first(),
            $last->last(),
        ]);
    }

    public function getEditLinkAttribute()
    {
        return route('websites.edit', $this->id);
    }

    public function getShowLinkAttribute()
    {
        return route('websites.show', $this->id);
    }

    public function getRobotsUrlAttribute()
    {
        return $this->url . '/robots.txt';
    }

    public function setUrlAttribute($value)
    {
        $parts = parse_url($value);

        $this->attributes['url'] = sprintf('%s://%s', $parts['scheme'], $parts['host']);
    }
}
