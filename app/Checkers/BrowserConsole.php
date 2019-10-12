<?php

namespace App\Checkers;

use App\CrawledPage;
use App\Crawler\Browsershot;

class BrowserConsole
{
    /**
     * @var CrawledPage
     */
    private $page;

    public function __construct(CrawledPage $page)
    {
        $this->page = $page;
    }

    public function run()
    {
        $this->fetch();
    }

    private function fetch()
    {
        $this->page->messages = Browsershot::url($this->page->url)
            ->setBinPath(app_path('Crawler/browser.js'))
            ->consoleOutput() ?: null;

        $this->page->save();
    }
}
