<?php

namespace App\Checkers;

use Exception;
use App\Website;
use Spatie\Crawler\Crawler;
use GuzzleHttp\RequestOptions;
use App\Crawler\CrawlObserver;
use Spatie\Crawler\CrawlInternalUrls;

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
    }

    private function fetch()
    {
        try {
            Crawler::create([
                RequestOptions::COOKIES => true,
                RequestOptions::CONNECT_TIMEOUT => 30,
                RequestOptions::TIMEOUT => 30,
                RequestOptions::HTTP_ERRORS => false,
                RequestOptions::VERIFY => false,
                RequestOptions::ALLOW_REDIRECTS => true,
                RequestOptions::HEADERS => [
                    'User-Agent' => config('app.user_agent'),
                ],
            ])
                ->ignoreRobots()
//                ->executeJavaScript()
                ->setDelayBetweenRequests(1000)
                ->setConcurrency(3)
                ->setCrawlObserver(new CrawlObserver($this->website))
                ->setCrawlProfile(new CrawlInternalUrls($this->website->url))
                ->startCrawling($this->website->url);
        } catch (Exception $exception) {
            logger()->error($exception->getMessage());
        }
    }
}
