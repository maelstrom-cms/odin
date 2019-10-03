<?php

namespace App\Http\Controllers;

use App\Website;
use App\CronPing;
use Illuminate\Http\Request;

class CronPingController extends Controller
{

    public function before(Request $request, Website $website)
    {
        $this->authorise($request, $website);
        CronPing::before($request, $website);

        return $this->response();
    }

    public function after(Request $request, Website $website)
    {
        $this->authorise($request, $website);
        CronPing::after($request, $website);

        return $this->response();
    }

    private function response()
    {
        return response('OK', 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }

    private function authorise(Request $request, Website $website)
    {
        abort_unless($request->filled('key'), 401);
        abort_unless($website->cron_enabled, 404);
        abort_unless($request->input('key') === $website->cron_key, 401);
    }
}
