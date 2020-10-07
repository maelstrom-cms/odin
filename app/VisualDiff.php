<?php

namespace App;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VisualDiff extends Model
{
    protected $fillable = [
        'url',
        'screenshot',
        'diff_found',
        'diff_path',
        'compared_with',
    ];

    protected $casts = [
        'diff_found' => 'boolean',
    ];

    protected $onceListeners = [];

    public function __get($key)
    {
        if (Str::startsWith($key, '___once_listener__')) {

            return $this->onceListeners[$key];
        }

        return parent::__get($key);
    }

    public function __set($key, $value)
    {
        if (Str::startsWith($key, '___once_listener__')) {

            $this->onceListeners[$key] = $value;

            return;
        }

        parent::__set($key, $value);
    }

    public function comparedWith()
    {
        return $this->belongsTo(VisualDiff::class, 'compared_with');
    }

    public function getFullScreenshotPathAttribute()
    {
        return Storage::disk('screenshots')->path($this->screenshot);
    }

    public function getScreenshotUrlAttribute()
    {
        return Storage::disk('screenshots')->url($this->screenshot);
    }

    public function getDiffUrlAttribute()
    {
        return Storage::disk('screenshots')->url($this->diff_path);
    }

    public function getImageAttribute()
    {
        return once(function () {
            return Image::make(
                $this->full_screenshot_path
            );
        });
    }
}
