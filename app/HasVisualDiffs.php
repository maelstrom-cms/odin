<?php

namespace App;

trait HasVisualDiffs
{
    public function visualDiffs()
    {
        return $this->hasMany(VisualDiff::class);
    }

    public function getLastVisualDiffsAttribute()
    {
        return $this->visualDiffs()->orderBy('created_at', 'desc')->take(2)->get();
    }

    public function getVisualUrlsToScanAttribute()
    {
        return collect(explode("\n", $this->visual_diff_urls))->map(function ($url) {
            return trim($url);
        })->filter(function ($url) {
            return filter_var($url, FILTER_VALIDATE_URL);
        })->values();
    }

}
