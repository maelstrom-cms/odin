<?php

namespace App\Http\Controllers;

use App\Website;
use App\Jobs\DnsCheck;
use Illuminate\Http\Request;

class DnsCompareController extends Controller
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
            DnsCheck::dispatchNow($website);
        }

        $scans = $website->last_dns_scans;

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
