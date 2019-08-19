<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasUptime;
    use HasRobots;
    use HasCertificates;
    use HasDns;
    use HasOpenGraph;

    protected $fillable = [
        'url',
        'user_id',
        'ssl_enabled',
        'uptime_enabled',
        'uptime_keyword',
        'robots_enabled',
        'dns_enabled',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('team', function (Builder $builder) {
            $user = auth()->user()->id;
            $builder->where('user_id', $user);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getEditLinkAttribute()
    {
        return route('websites.edit', $this->id);
    }

    public function getShowLinkAttribute()
    {
        return route('websites.show', $this->id);
    }

    public function setUrlAttribute($value)
    {
        $parts = parse_url($value);

        $this->attributes['url'] = sprintf('%s://%s', $parts['scheme'], $parts['host']);
    }
}
