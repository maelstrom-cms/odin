<?php

namespace App\Checkers;

use Embed\Embed;
use App\Website;
use App\OpenGraphScan;

class OpenGraph
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
        $info = Embed::create($this->website->url);

        $scan = new OpenGraphScan([
            'title' => $info->title,
            'description' => $info->description,
            'image' => $info->image,
            'url' => $info->url,
            'icon' => $info->providerIcon,
        ]);

        $this->website->openGraph()->save($scan);
    }
}
