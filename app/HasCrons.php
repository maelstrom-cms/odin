<?php

namespace App;

trait HasCrons
{
    public function cronPings()
    {
        return $this->hasMany(CronPing::class);
    }
}
