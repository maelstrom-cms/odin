<?php

namespace App\Checkers;

use App\Website;
use App\CrawledPage;
use App\Crawler\Browsershot;
use App\Notifications\BrowserMessageDetected;

class BrowserConsole
{
    /**
     * @var CrawledPage
     */
    private $page;

    /**
     * @var Website
     */
    private $website;

    public function __construct(Website $website, CrawledPage $page)
    {
        $this->page = $page;
        $this->website = $website;
    }

    public function run()
    {
        $this->fetch();
        $this->notify();
    }

    private function fetch()
    {
        $this->page->messages = Browsershot::url($this->page->url)
            ->setBinPath(app_path('Crawler/browser.js'))
            ->windowSize(1440, 900)
            ->consoleOutput() ?: null;

        $this->page->save();
    }

    private function notify()
    {
        if (empty($this->page->messages)) {
            return;
        }

        $this->website->user->notify(
            new BrowserMessageDetected($this->website, $this->page)
        );
    }
}
