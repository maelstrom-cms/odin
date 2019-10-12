<?php

namespace App\Crawler;

use Exception;
use Spatie\Browsershot\Browsershot as SpatieBrowsershot;

class Browsershot extends SpatieBrowsershot
{
    protected function somethingHarmless()
    {
        $url = $this->html ? $this->createTemporaryHtmlFile() : $this->url;

        return $this->createCommand($url, 'browserContext', []);
    }

    public function consoleOutput()
    {
        $command = $this->somethingHarmless();

        try {
            return $this->callBrowser($command);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
