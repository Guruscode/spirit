<?php

namespace App\Traits;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http as HttpFacade;

trait Http
{
    protected function http(): PendingRequest
    {
        return HttpFacade::withUserAgent(config('scan.useragent'));
    }
}
