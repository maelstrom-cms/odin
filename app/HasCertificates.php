<?php

namespace App;

trait HasCertificates
{
    public function certificates()
    {
        return $this->hasMany(CertificateScan::class);
    }

    public function getCertificateHostnameAttribute()
    {
        return parse_url($this->url, PHP_URL_HOST);
    }
}
