<?php

namespace App\Http\Controllers;

use App\Website;
use Illuminate\Http\Request;
use App\Jobs\CertificateCheck;

class StatusReportController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return array
     */
    public function __invoke(Request $request, Website $website)
    {
        $results = [];
        foreach (Website::all() as $site) {
            $results[$site->url]['uptime'] = $site->generateUptimeReport();
            $results[$site->url]['ssl'] = $site->certificates()->latest()->first();
            $results[$site->url]['dns'] = $site->last_dns_scans;
            $results[$site->url]['robots'] = $site->last_robot_scans;
        }

        return $results;
    }
}
