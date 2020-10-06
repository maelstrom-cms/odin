<?php

namespace App\Checkers;

use Exception;
use Ramsey\Uuid\Uuid;
use App\VisualDiff as Model;
use Spatie\Image\Manipulations;
use Spatie\Browsershot\Browsershot;
use App\Website;
use SebastianBergmann\Diff\Differ;
use Intervention\Image\Facades\Image;
use App\Notifications\RobotsHasChanged;
use Illuminate\Support\Facades\Storage;
use App\Notifications\VisualDifferenceFound;

class VisualDiff
{
    private $website;

    private $scan;

    private $url;

    private $diff;

    public function __construct(Website $website, $url)
    {
        $this->website = $website;
        $this->url = $url;
    }

    public function run()
    {
        $this->fetch();
        $this->compare();
        $this->notify();
    }

    private function fetch()
    {
        $filename = (string) Uuid::uuid4() . '.png';

//        $this->scan = new Model([
//            'url' => $this->url,
////            'screenshot' => $filename,
//            'screenshot' => '80df5ce1-0edf-46e3-a222-090069cb6a48.png',
//        ]);

        $this->scan = $this->website->visualDiffs()
            ->latest()
            ->where('url', $this->url)
            ->first();

        try {
//            Browsershot::url($this->url)
//                ->windowSize(1440, 1024)
//                ->fullPage()
//                ->waitUntilNetworkIdle()
//                ->setDelay(1000)
//                ->save(
//                    Storage::disk('screenshots')->path($filename)
//                );

//            $this->website->visualDiffs()->save($this->scan);
        } catch (Exception $exception) {
            if (app()->environment('dev')) {
                throw $exception;
            }
        }
    }

    private function compare()
    {
        $lastScan = $this->website->visualDiffs()
            ->latest()
            ->where('id', '!=', $this->scan->id)
            ->where('url', $this->url)
            ->first();

        if (!$lastScan) {
            return;
        }

        if ($lastScan->image->getHeight() > $this->scan->image->getHeight()) {
            $this->scan->image->resizeCanvas(null, $lastScan->image->getHeight(), 'top-left')->save();
        } else {
            $lastScan->image->resizeCanvas(null, $this->scan->image->getHeight(), 'top-left')->save();
        }

        $differ = \BeyondCode\VisualDiff\VisualDiff::diff(
            $lastScan->full_screenshot_path,
            $this->scan->full_screenshot_path
        );

        $this->scan->diff_path = 'diff-' . $this->scan->id . '-' . $lastScan->id . '.png';
        $this->scan->compared_with = $lastScan->id;

        try {
            $diff = $differ->save(
                Storage::disk('screenshots')->path($this->scan->diff_path)
            );

            $this->scan->diff_found = data_get($diff, 'pixels', 0) > 10;
        } catch (Exception $exception) {
            if (app()->environment('dev')) {
                throw $exception;
            }
        }

        $this->scan->save();
    }

    private function notify()
    {
        if (!$this->scan) {
            return null;
        }

        if (empty($this->scan->diff_found)) {
            return null;
        }

         $this->website->user->notify(
             new VisualDifferenceFound($this->website, $this->scan)
         );
    }
}
