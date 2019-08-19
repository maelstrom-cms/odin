<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CertificateScan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'additional_domains' => 'array',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'was_valid' => 'boolean',
        'did_expire' => 'boolean',
    ];

    protected $appends = [
        'expires_in',
    ];

    public function getExpiresInAttribute()
    {
        return now()->diffAsCarbonInterval($this->valid_to)->forHumans(['join' => true]);
    }
}
