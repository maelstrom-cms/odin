<?php

namespace App\Http\Controllers;

use App\Website;
use App\Jobs\UptimeCheck;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CronReportController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Website $website
     * @return Response
     */
    public function __invoke(Request $request, Website $website)
    {
        $website->load(['cronPings']);

        $limit = is_numeric($request->input('limit')) ? (int) $request->input('limit', 20) : 20;

        $query = $website->cronPings()->orderBy('created_at', 'desc');

        $total = $query->count();
        $events = $query->take($limit)->get();
        $found = $events->count();

        $response = [
            'limit' => $limit,
            'total' => $total,
            'events' => $events,
            'found' => $found,
        ];

        return response($response);
    }
}
