<?php

namespace App;

trait HasOpenGraph
{
    public function openGraph()
    {
        return $this->hasMany(OpenGraphScan::class);
    }
}
