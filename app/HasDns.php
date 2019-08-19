<?php

namespace App;

trait HasDns
{
    public function dns()
    {
        return $this->hasMany(DnsScan::class);
    }

    public function getLastDnsScansAttribute()
    {
        return $this->dns()->orderBy('created_at', 'desc')->take(2)->get();
    }
    public function getDnsHostnameAttribute()
    {
        return str_replace('www.', '', parse_url($this->url, PHP_URL_HOST));
    }
}
