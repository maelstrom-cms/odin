<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class CronPing extends Model
{
    protected $fillable = [
        'website_id',
        'event',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public static function before(Request $request, Website $website)
    {
        return static::create([
            'website_id' => $website->getKey(),
            'event' => 'started',
            'payload' => $request->except(['key']),
        ]);
    }

    public static function after(Request $request, Website $website)
    {
        return static::create([
            'website_id' => $website->getKey(),
            'event' => 'stopped',
            'payload' => $request->except(['key']),
        ]);
    }
}
