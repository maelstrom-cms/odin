<?php

namespace App\Checkers;

use Exception;
use App\DnsScan;
use App\Website;
use App\CrawlObserver;
use Spatie\Crawler\Crawler;
use GuzzleHttp\RequestOptions;
use Whoisdoma\DNSParser\DNSParser;
use SebastianBergmann\Diff\Differ;
use App\Notifications\DnsHasChanged;

class Page
{
    private $website;

    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    public function run()
    {
        $this->fetch();
        $this->compare();
        $this->notify();
    }

    private function fetch()
    {
        Crawler::create([
            RequestOptions::COOKIES => true,
            RequestOptions::CONNECT_TIMEOUT => 10,
            RequestOptions::TIMEOUT => 10,
            RequestOptions::ALLOW_REDIRECTS => false,
            RequestOptions::HEADERS => [
                'User-Agent' => '',
            ],
        ])
            ->ignoreRobots()
            ->setConcurrency(2)
            ->executeJavaScript()
            ->setCrawlObserver(new CrawlObserver($this->website))
            ->startCrawling($this->website->url);
    }

    private function compare()
    {

    }

    private function notify()
    {

    }
}
