<?php

namespace App;

trait HasIntermediateCertificates
{
    public function intermediate_certificates()
    {
	$scan = $this->website->certificates()->latest()->first();
        return $scan->hasMany(IntermediateCertificateScan::class);
    }

    public function getCertificateHostnameAttribute()
    {
        return parse_url($this->url, PHP_URL_HOST);
    }
}

