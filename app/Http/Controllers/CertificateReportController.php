<?php

namespace App\Http\Controllers;

use App\Website;
use Illuminate\Http\Request;
use App\Jobs\CertificateCheck;

class CertificateReportController extends Controller
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
            CertificateCheck::dispatchNow($website);
        }

        $scan = $website->certificates()->latest()->first();

        return $scan;
    }
}
