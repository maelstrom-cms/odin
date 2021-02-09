<?php

namespace App\Http\Controllers;

use App\VisualDiff;
use App\CrawledPage;
use App\Jobs\PageCheck;
use App\Website;
use Exception;
use App\Jobs\VisualDiffCheck;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VisualDiffsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Website $website
     * @return Application|ResponseFactory|Response|void
     * @throws Exception
     */
    public function __invoke(Request $request, Website $website)
    {
        if ($request->has('refresh')) {
            $this->scan($website);
        }

        return $website->visualUrlsToScan->map(function ($url) use ($website) {
            $result = $website->visualDiffs()
                ->where('url', $url)
                ->take(2)
                ->latest()
                ->get();

            if ($result->count() !== 2) {
                return null;
            }

            $previous = $result->last();
            $current = $result->first();

            if (!$previous->diff_found&& !$current->diff_found) {
                return null;
            }

            return [
                'url' => $previous->url,
                'previous' => $previous->screenshot_url,
                'current' => $current->screenshot_url,
                'diff' => $current->diff_url,
                'date' => $current->created_at,
            ];
        })->filter()->values();
    }

    /**
     * @param Website $website
     * @return ResponseFactory|Response
     */
    public function scan(Website $website)
    {
        $website->visual_urls_to_scan->each(function ($url) use ($website) {
            VisualDiffCheck::dispatch($website, $url);
        });

        return response([
            'success' => true,
        ]);
    }
}
