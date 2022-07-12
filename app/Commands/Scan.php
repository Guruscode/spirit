<?php

namespace App\Commands;

class Scan extends Command
{
    /** @var string */
    protected $signature = 'scan {--domain= : Domain name to scan} {--path= : Local path to scan}';

    /** @var string */
    protected $description = 'Run a complete scan on a domain and codebase.';

    public function handle()
    {
        $this->header();

        $path = $this->option('path');
        $domain = $this->option('domain');

        if ($domain) {
            $this->call('scan:headers', ['domain' => $domain]);
            $this->call('scan:securitytxt', ['domain' => $domain]);
            $this->call('scan:subdomains', ['domain' => $domain]);
        }

        if ($path) {
            $this->call('scan:dependencies', ['path' => $path]);
            $this->call('scan:emails', ['path' => $path]);
            $this->call('scan:secrets', ['path' => $path]);
        }
    }
}
