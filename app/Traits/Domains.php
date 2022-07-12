<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Domains
{
    protected function domain()
    {
        $domain = $this->argument('domain');

        return Str::startsWith($domain, 'http')
            ? $domain
            : "https://{$domain}";
    }
}
