<?php

namespace App\Checkers;

use Exception;
use App\DnsScan;
use App\Website;
use Whoisdoma\DNSParser\DNSParser;
use SebastianBergmann\Diff\Differ;
use App\Notifications\DnsHasChanged;

class Dns
{
    private $website;

    private $scan;

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
        try {
            $response = (new DNSParser('array'))->lookup($this->website->dns_hostname);
        } catch (Exception $e) {
            return logger()->error($e->getMessage());
        }

        $flat = collect($response['records'])->transform(function ($item) {
            return sprintf(
                '%s %s %s',
                $item['host'],
                $item['type'],
                $item['ip'] ?? $item['target'] ?? $item['mname'] ?? $item['txt'] ?? $item['ipv6'] ?? '',
            );
        })->sort()->values()->implode("\n");

        $scan = new DnsScan([
            'records' => $response['records'],
            'flat' => $flat,
        ]);

        $this->website->dns()->save($scan);
    }

    private function compare()
    {
        $scans = $this->website->last_dns_scans;

        if ($scans->isEmpty() || $scans->count() === 1) {
            return;
        }

        $diff = (new Differ)->diff(
            $scans->last()->flat,
            $scans->first()->flat
        );

        $placeholder = '--- Original
+++ New
';

        if ($diff === $placeholder) {
            $diff = null;
        }

        $scans->first()->diff = $diff;
        $scans->first()->save();

        $this->scan = $scans->first();
    }

    private function notify()
    {
        if (!$this->scan) {
            return null;
        }

        if (empty($this->scan->diff)) {
            return null;
        }

        $this->website->user->notify(
            new DnsHasChanged($this->website, $this->scan)
        );
    }
}
