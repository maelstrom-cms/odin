<?php

namespace App\Http\Controllers;

use Exception;
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
     * @throws Exception
     */
    public function __invoke(Request $request, Website $website)
    {
        if ($request->has('refresh')) {
            UptimeCheck::dispatchNow($website);
        }

        $response = $website->generateUptimeReport();

        return response($response);
    }
}
