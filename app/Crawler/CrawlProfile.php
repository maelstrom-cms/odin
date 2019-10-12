<?php

namespace App\Crawler;

use Illuminate\Support\Str;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlInternalUrls;

class CrawlProfile extends CrawlInternalUrls
{
    public function shouldCrawl(UriInterface $url): bool
    {
        // Only internal link scanning is desired.
        if (!Str::startsWith($url, $this->baseUrl)) {
            return false;
        }

        // Very basic, should be improved over time.
        if (Str::contains($url, ['search', '.xml', '.jpg', '.jpeg', '.png', '.gif', '.svg', '.pdf'])) {
            return false;
        }

        if (Str::contains($url, 'twitter')) {
            return false;
        }

        return true;
    }
}
