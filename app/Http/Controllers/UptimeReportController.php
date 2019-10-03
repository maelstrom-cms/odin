<?php

namespace App\Http\Controllers;

use App\Website;
use App\Jobs\UptimeCheck;
use Illuminate\Http\Request;

class UptimeReportController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Website $website
     * @return array
     */
    public function __invoke(Request $request, Website $website)
    {
        if ($request->has('refresh')) {
            UptimeCheck::dispatchNow($website);
        }

        $website->load(['uptimes']);

        $response = [
            'uptime' => $website->uptime_summary,
            'response_time' => $website->response_time,
            'response_times' => $website->response_times,
            'online' => $website->current_state,
            'online_time' => $website->uptime,
            'last_incident' => $website->last_incident,
            'events' => $website->recent_events,
        ];

        return response($response);
    }
}
