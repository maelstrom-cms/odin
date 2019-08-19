<?php

namespace App\Checkers;

use App\Website;
use App\UptimeScan;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use SebastianBergmann\Diff\Differ;


class Uptime
{
    private $website;

    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    public function run()
    {
        $this->fetch();
        $this->notify();
    }

    private function fetch()
    {
        $client = new Client();

        $response_time = 3001;

        $response = $client->request('GET', $this->website->url, [
            'on_stats' => function ($stats) use (&$response_time) {
                $response_time = $stats->getTransferTime();
            },
            'verify' => false,
            'allow_redirects' => true,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0+(compatible; UptimeRobot/2.0; http://www.uptimerobot.com/; Odin)'
            ],
        ]);

        $keywordFound = Str::contains($response->getBody(), $this->website->uptime_keyword);

        if (!$keywordFound && $response->getStatusCode() == '200') {
            $reason = sprintf('Keyword: %s not found (%d)', $this->website->uptime_keyword, 200);
        } else {
            $reason = sprintf('%s (%d)', $response->getReasonPhrase(), $response->getStatusCode());
        }

        $scan = new UptimeScan([
            'response_status' => $reason,
            'response_time' => $response_time,
            'was_online' => $keywordFound,
        ]);

        $this->website->uptimes()->save($scan);
    }

    private function notify()
    {

    }
}
