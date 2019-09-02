<?php

namespace App;

use App\Jobs\DnsCheck;
use App\Jobs\RobotsCheck;
use App\Jobs\UptimeCheck;
use App\Jobs\OpenGraphCheck;
use App\Jobs\CertificateCheck;
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
            if (!app()->runningInConsole()) {
                $user = auth()->user()->id;
                $builder->where('user_id', $user);
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Runs all the checks for the website.
     */
    public function runInitialScans()
    {
        CertificateCheck::dispatch($this);
        DnsCheck::dispatch($this);
        OpenGraphCheck::dispatch($this);
        RobotsCheck::dispatch($this);
        UptimeCheck::dispatch($this);
    }

    /**
     * @return string
     */
    public function getEditLinkAttribute()
    {
        return route('websites.edit', $this->id);
    }

    /**
     * @return string
     */
    public function getShowLinkAttribute()
    {
        return route('websites.show', $this->id);
    }

    /**
     * @param $value
     */
    public function setUrlAttribute($value)
    {
        $parts = parse_url($value);

        $this->attributes['url'] = sprintf('%s://%s', $parts['scheme'], $parts['host']);
    }
}
