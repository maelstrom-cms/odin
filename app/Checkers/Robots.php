<?php

namespace App\Checkers;

use App\RobotScan;
use GuzzleHttp\Client;
use App\Website;
use SebastianBergmann\Diff\Differ;


class Robots
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
    }

    private function fetch()
    {
        $client = new Client();

        $response = $client->request('GET', $this->website->robots_url);

        $scan = new RobotScan([
            'txt' => (string) $response->getBody()
        ]);

        $this->website->robots()->save($scan);
    }

    private function compare()
    {
        $scans = $this->website->last_robot_scans;

        if ($scans->isEmpty() || $scans->count() === 1) {
            return;
        }

        $diff = (new Differ)->diff($scans->last()->txt, $scans->first()->txt);

        $placeholder = '--- Original
+++ New
';

        if ($diff === $placeholder) {
            $diff = null;
        }

        $scans->first()->diff = $diff;
        $scans->first()->save();
    }
}
