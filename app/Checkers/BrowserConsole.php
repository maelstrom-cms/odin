<?php

namespace App\Checkers;

use App\Website;
use App\CrawledPage;
use Illuminate\Support\Str;
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
        $messages = Browsershot::url($this->page->url)
            ->setBinPath(app_path('Crawler/browser.js'))
            ->windowSize(1440, 900)
            ->consoleOutput() ?: null;

        if ($this->shouldReport($messages)) {
            $this->page->messages = $messages;
        } else {
            $this->page->messages = null;
        }

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

    private function shouldReport($messages)
    {
        if (empty($messages)) {
            return false;
        }

        foreach (config('app.ignore_console_errors') as $phrase) {
            if (Str::contains($messages, $phrase)) {
                return false;
            }
        }

        return true;
    }
}
