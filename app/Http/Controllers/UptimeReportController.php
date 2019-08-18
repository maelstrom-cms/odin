<?php

namespace App\Http\Controllers;

use App\UptimeScan;
use App\Website;
use Illuminate\Http\Request;

class UptimeReportController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Website $website
     * @return array
     */
    public function __invoke(Request $request, Website $website)
    {
        $website->load(['uptimes']);

        $response = [
            'uptime' => $website->uptime_summary,
            'response_time' => $website->response_time,
            'response_times' => $website->response_times,
            'online' => $website->current_state,
            'online_time' => $website->uptime,
            'last_incident' => $website->last_incident,
            'events' => $website->recent_events->transform(function (UptimeScan $scan) {
                return [
                    'id' => $scan->getKey(),
                    'date' => $scan->created_at,
                    'type' => $scan->was_online ? 'up' : 'down',
                    'reason' => $scan->response_status,
                    'duration' => 10,
                ];
            })->values(),
        ];

        // return view('debug', ['data' => $response]);
        return response($response);
    }
}
