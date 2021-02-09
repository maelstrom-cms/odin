<?php

namespace App\Crawler;

use App\Website;
use App\CrawledPage;
use Illuminate\Support\Str;
use App\Jobs\BrowserConsoleCheck;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Spatie\Crawler\CrawlObserver as SpatieCrawlObserver;

class CrawlObserver extends SpatieCrawlObserver
{
    /**
     * @var Website
     */
    private $website;

    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    public function willCrawl(UriInterface $url)
    {
        if (!Str::startsWith($url, $this->website->url)) {
            return false;
        }

        $page = $this->website->crawledPages()->firstOrCreate([
            'url' => $url,
        ]);

        $page->save();
    }

    /**
     * Called when the crawler has crawled the given url successfully.
     *
     * @param UriInterface $url
     * @param ResponseInterface $response
     * @param UriInterface|null $foundOnUrl
     * @return bool
     */
    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null)
    {
        $page = $this->website->crawledPages()->where('url', $url)->first();

        if (!$page) {
            return false;
        }

        $page->exception = null;
        $page->response = $response->getStatusCode() . ' - ' . $response->getReasonPhrase();

        if ($foundOnUrl) {
            $pages = [];

            if (!empty($page->found_on)) {
                $pages = $page->found_on;
            }

            $pages[] = (string) $foundOnUrl;

            $page->found_on = array_unique($pages);
        }

        BrowserConsoleCheck::dispatch($this->website, $page);

        return $page->save();
    }

    /**
     * Called when the crawler had a problem crawling the given url.
     *
     * @param UriInterface $url
     * @param RequestException $requestException
     * @param UriInterface|null $foundOnUrl
     * @return bool
     */
    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null)
    {
        $page = $this->website->crawledPages()->where('url', $url)->first();

        if (!$page) {
            return false;
        }

        $page->response = null;
        $page->exception = $requestException->getCode() . ' - ' . $requestException->getMessage();

        BrowserConsoleCheck::dispatch($this->website, $page);

        return $page->save();
    }

    private function notify(CrawledPage $page)
    {
//        dump($page->exception);
    }
}
