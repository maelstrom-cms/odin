<?php

namespace App\Http\Controllers;

use App\Jobs\RobotsCheck;
use App\Website;
use Illuminate\Http\Request;

class RobotCompareController extends Controller
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
            RobotsCheck::dispatchNow($website);
        }

        $scans = $website->last_robot_scans;

        if ($scans->isEmpty()) {
            return [
                'now' => null,
                'previous' => null,
            ];
        }

        return [
            'now' => $scans[0],
            'previous' => $scans[1] ?? null,
        ];
    }
}
