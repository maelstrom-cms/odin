<?php

namespace App;

use Exception;
use App\Jobs\DnsCheck;
use App\Jobs\RobotsCheck;
use App\Jobs\UptimeCheck;
use App\Jobs\OpenGraphCheck;
use App\Jobs\CertificateCheck;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Website extends Model
{
    use HasUptime;
    use HasRobots;
    use HasCertificates;
    use HasDns;
    use HasOpenGraph;
    use HasCrons;

    protected $fillable = [
        'url',
        'user_id',
        'ssl_enabled',
        'uptime_enabled',
        'uptime_keyword',
        'robots_enabled',
        'dns_enabled',
        'cron_enabled',
        'cron_key',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('team', function (Builder $builder) {
            $request = request();

            if ($request->filled('key')) {
                $builder->where('cron_key', $request->input('key'));
            } elseif (!app()->runningInConsole()) {
                if (auth()->check()) {
                    $builder->where('user_id', auth()->id());
                } else {
                    $builder->where('user_id', '-1');
                }
            }
        });
    }

    /**
     * @return BelongsTo
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
        try {
            OpenGraphCheck::dispatch($this);

            if ($this->ssl_enabled) {
                CertificateCheck::dispatch($this);
            }

            if ($this->dns_enabled) {
                DnsCheck::dispatch($this);
            }

            if ($this->robots_enabled) {
                RobotsCheck::dispatch($this);
            }

            if ($this->uptime_enabled) {
                UptimeCheck::dispatch($this);
            }
        } catch (Exception $e) {
            logger($e->getMessage());
        }
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
