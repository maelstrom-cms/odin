<?php


namespace App;


use Illuminate\Support\Collection;

trait HasUptime
{
    public function uptimes()
    {
        return $this->hasMany(UptimeScan::class)->orderBy('created_at', 'desc');
    }

    public function getRecentEventsAttribute()
    {
        return $this->uptimes->sortByDesc('created_at')->take(10);
    }

    public function getLastIncidentAttribute()
    {
        $event = $this->uptimes
            ->where('was_online', 0)
            ->sortByDesc('created_at')
            ->first();

        if (!$event) {
            return 'No downtime recorded';
        }

        $downtime = 'xxx';
        $when = $event->created_at->format('D jS M "y @ h:ia');

        return sprintf('Was down for %s on %s', $downtime, $when);

        return 'Will update later';
    }

    public function getCurrentStateAttribute()
    {
        $event = $this->uptimes->sortByDesc('created_at')->first();

        if (!$event) {
            return true;
        }

        return $event->was_online;
    }

    public function getUptimeAttribute()
    {
        $latest = $this->uptimes->sortByDesc('created_at')->first();
        $online = $this->uptimes->firstWhere('was_online', 1);
        $offline = $this->uptimes->firstWhere('was_online', 0);

        if (!$latest) {
            return 'Still collecting data...';
        }

        if ($latest->offline) {
            if ($offline && $online) {
                return now()->diffAsCarbonInterval($online->created_at)->forHumans(['join' => true]);
            }

            return 'Site never previously recorded as online.';
        }

        if (!$offline) {
            return now()->diffAsCarbonInterval($online->created_at)->forHumans(['join' => true]);
        }

        return $offline->created_at->diffAsCarbonInterval(now())->forHumans(['join' => true]);
    }

    public function getResponseTimesAttribute()
    {
        return $this->uptimes
            ->sortByDesc('created_at')
            ->take(25)
            ->transform(function (UptimeScan $scan) {
               return [
                   'date' => $scan->created_at,
                   'value' => $scan->response_time,
               ];
            })
            ->values();
    }

    public function getResponseTimeAttribute()
    {
        $time = $this->response_times->first();

        if (!$time) {
            return '-';
        }

        return $time['value'];
    }

    public function getUptimeSummaryAttribute()
    {
        /* @var Collection $events */
        $events = $this->uptimes;

        $upCount = $events->where('was_online', 1);
        $totalPercentage = ($upCount->count() * 100) / $events->count();

        $today = now();
        $lastWeek = now()->subDays(7);
        $lastMonth = now()->subDays(30);

        $todayEvents = $events->filter(function ($item) use ($today) {
            return $item->created_at->format('d/m/y') === $today->format('d/m/y');
        });

        $todayUpEvents = $todayEvents->where('was_online', 1);
        $todayPercentage = ($todayUpEvents->count() * 100) / $todayEvents->count();

        $weeklyEvents = $events->filter(function ($item) use ($lastWeek, $today) {
            return $item->created_at->isBetween($lastWeek, $today);
        });

        $weeklyUpEvents = $weeklyEvents->where('was_online', 1);
        $weeklyPercentage = ($weeklyUpEvents->count() * 100) / $weeklyEvents->count();

        $monthlyEvents = $events->filter(function ($item) use ($lastMonth, $today) {
            return $item->created_at->isBetween($lastMonth, $today);
        });

        $monthlyUpEvents = $monthlyEvents->where('was_online', 1);
        $monthlyPercentage = ($monthlyUpEvents->count() * 100) / $monthlyEvents->count();

        return [
            'total' => floor($totalPercentage),
            'day' => floor($todayPercentage),
            'week' => floor($weeklyPercentage),
            'month' => floor($monthlyPercentage),
        ];
    }
}
