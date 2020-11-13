<?php

namespace App\Http\Controllers;

use App\CrawledPage;
use App\Jobs\PageCheck;
use App\Website;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProblematicPageController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Website $website
     * @return Application|ResponseFactory|Response
     * @throws Exception
     */
    public function __invoke(Request $request, Website $website)
    {
        $query = $website
            ->crawledPages();

        $count = $query->count();

        if ($request->input('refresh')) {
            cache()->forget('pages_' . $website->id);
        }

        $pages = cache()->remember('pages_' . $website->id, 1400, function () use ($query) {
            return $query
                ->whereNotNull('response')
                ->where(function ($query) {
                    $query->whereNotNull('messages');
                    $query->orWhereNotNull('exception');
                    $query->orWhere('response', 'NOT LIKE', '%200%');
                })
                ->get()
                ->toArray();
        });

        return response([
            'count' => $count,
            'pages' => $pages,
        ]);
    }

    /**
     * @param Website $website
     * @return ResponseFactory|Response
     */
    public function scan(Website $website)
    {
        PageCheck::dispatch(
            $website
        );

        return response([
            'success' => true,
        ]);
    }

    /**
     * @param Request $request
     * @param Website $website
     * @param CrawledPage $page
     * @return Application|ResponseFactory|Response
     * @throws Exception
     */
    public function delete(Request $request, Website $website, CrawledPage $page)
    {
        abort_if($website->getKey() !== $page->website_id, 404);

        $page->delete();

        cache()->forget('pages_' . $website->id);

        return $this->__invoke($request, $website);
    }
}
