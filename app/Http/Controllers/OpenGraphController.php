<?php

namespace App\Http\Controllers;

use App\Jobs\OpenGraphCheck;
use App\Website;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OpenGraphController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request, Website $website)
    {
        if ($request->has('refresh')) {
            OpenGraphCheck::dispatchNow($website);
        }

        return $website->openGraph()->latest()->first();
    }
}
