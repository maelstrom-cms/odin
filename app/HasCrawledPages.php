<?php

namespace App;

trait HasCrawledPages
{
    public function crawledPages()
    {
        return $this->hasMany(CrawledPage::class);
    }
}
