<?php

namespace App\Http\Controllers;

use App\Jobs\OpenGraphCheck;
use App\Website;
use Illuminate\Http\Request;

class OpenGraphController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Website $website)
    {
        if ($request->has('refresh')) {
            OpenGraphCheck::dispatchNow($website);
        }

        return $website->openGraph()->latest()->first();
    }
}
