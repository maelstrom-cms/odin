<?php

namespace App;

use Exception;
use App\Jobs\DnsCheck;
use App\Jobs\RobotsCheck;
use App\Jobs\UptimeCheck;
use App\Jobs\OpenGraphCheck;
use App\Jobs\VisualDiffCheck;
use App\Jobs\CertificateCheck;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Website extends Model
{
    use HasDns;
    use HasCrons;
    use HasUptime;
    use HasRobots;
    use HasOpenGraph;
    use HasCertificates;
    use HasCrawledPages;
    use HasVisualDiffs;

    protected $fillable = [
        'url',
        'user_id',
        'ssl_enabled',
        'uptime_enabled',
        'uptime_keyword',
        'robots_enabled',
        'dns_enabled',
        'cron_enabled',
        'crawler_enabled',
        'cron_key',
        'visual_diff_urls',
        'visual_diff_enabled',
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
     * @param $builder
     * @param $type
     */
    public function scopeNotAlreadyQueued($builder, $type)
    {
        $builder->where('in_queue_' . $type, 0);
    }

    /**
     * @param $builder
     */
    public function scopeCanScanCertificates($builder)
    {
        $builder->notAlreadyQueued('ssl')
            ->where('ssl_enabled', 1);
    }

    /**
     * @param $builder
     */
    public function scopeCanCrawl($builder)
    {
        $builder->notAlreadyQueued('crawler')
            ->where('crawler_enabled', 1);
    }

    /**
     * @param $builder
     */
    public function scopeCanScanDns($builder)
    {
        $builder->notAlreadyQueued('dns')
            ->where('dns_enabled', 1);
    }

    /**
     * @param $builder
     */
    public function scopeCanScanOpenGraph($builder)
    {
        $builder->notAlreadyQueued('og');
    }

    /**
     * @param $builder
     */
    public function scopeCanScanRobots($builder)
    {
        $builder->notAlreadyQueued('robots')
            ->where('robots_enabled', 1);
    }

    /**
     * @param $builder
     */
    public function scopeCanScanUptime($builder)
    {
        $builder->notAlreadyQueued('uptime')
            ->where('uptime_enabled', 1);
    }

    /**
     * @param $builder
     */
    public function scopeCanScanVisualDiffs($builder)
    {
        $builder->where('visual_diff_enabled', 1);
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

            if ($this->visual_diff_enabled) {
                VisualDiffCheck::dispatch($this);
            }
        } catch (Exception $e) {
            logger($e->getMessage());
        }
    }

    public function queue(string $type)
    {
        $this->{'in_queue_' . $type} = 1;
        $this->save();
    }

    public function unqueue(string $type)
    {
        $this->{'in_queue_' . $type} = 0;
        $this->save();
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
