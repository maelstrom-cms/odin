<?php

namespace App\Checkers;

use Exception;
use App\Website;
use App\UptimeScan;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use GuzzleHttp\RequestOptions;
use App\Jobs\CacheUptimeReport;
use App\Notifications\WebsiteIsDown;
use App\Notifications\WebsiteIsBackUp;

class Uptime
{
    private $website;

    private const RETRY_TIMES = 3;

    private const RETRY_SLEEP_MILLISECONDS = 5000;

    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    public function run()
    {
        $this->fetch();
        $this->notify();
        $this->cache();
    }

    private function tryRequest()
    {
        $responseTime = 3001;
        $keywordFound = false;

        try {
            $response = (new Client)->request('GET', $this->website->url, [
                RequestOptions::ON_STATS => function ($stats) use (&$responseTime) {
                    $responseTime = $stats->getTransferTime();
                },
                RequestOptions::HTTP_ERRORS => false,
                RequestOptions::VERIFY => false,
                RequestOptions::ALLOW_REDIRECTS => true,
                RequestOptions::HEADERS => [
                    'User-Agent' => config('app.user_agent'),
                ],
                RequestOptions::CONNECT_TIMEOUT => 20,
                RequestOptions::READ_TIMEOUT => 20,
                RequestOptions::TIMEOUT => 60,
                RequestOptions::DEBUG => false,
            ]);

            $responseBody = mb_strtolower($response->getBody());
            $keywordFound = Str::contains($responseBody, mb_strtolower($this->website->uptime_keyword));

            if (!$keywordFound && $response->getStatusCode() == '200') {
                $reason = sprintf('Keyword: %s not found (%d)', $this->website->uptime_keyword, 200);
            } else {
                $reason = sprintf('%s (%d)', $response->getReasonPhrase(), $response->getStatusCode());
            }
        } catch (Exception $exception) {
            $reason = $exception->getMessage();
            $responseBody = $exception->getTraceAsString();
        }

        $data = [
            'response_status' => $reason,
            'response_body' => $keywordFound ? '' : $responseBody,
            'was_online' => $keywordFound,
            'response_time' => $responseTime,
        ];

        if (!$keywordFound) {
            $exception = new Exception($reason);
            $exception->data = $data;

            throw $exception;
        }

        return $data;
    }

    private function fetch()
    {
        try {
            $data = retry(static::RETRY_TIMES, function () {
                return $this->tryRequest();
            }, static::RETRY_SLEEP_MILLISECONDS);
        } catch (Exception $exception) {
            $data = $exception->data;
        }

        $scan = new UptimeScan($data);
        $this->website->uptimes()->save($scan);
    }

    private function notify()
    {
        $lastTwo = $this->website->uptimes()->orderBy('created_at', 'DESC')->take(2)->get();

        if ($lastTwo->count() !== 2) {
            return null;
        }

        $now = $lastTwo->first();
        $previous = $lastTwo->last();

        if ($now->online && $previous->offline) {
            return $this->website->user->notify(
                new WebsiteIsBackUp($this->website)
            );
        } elseif ($now->offline && $previous->online) {
            return $this->website->user->notify(
                new WebsiteIsDown($this->website)
            );
        }
    }

    private function cache()
    {
        CacheUptimeReport::dispatch($this->website);
    }
}
