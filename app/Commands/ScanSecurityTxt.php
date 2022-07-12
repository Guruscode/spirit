<?php

namespace App\Commands;

use App\Traits\Domains;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Http;

class ScanSecurityTxt extends Command
{
    use Domains;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'scan:securitytxt {domain}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Look for a security.txt file on the domain';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->header();
        $domain = $this->domain();

        $this->line("Looking for security.txt: <info>{$domain}</info>");

        $this->checkUrl($domain.'/security.txt');
        $this->checkUrl($domain.'/.well-known/security.txt');
    }

    public function checkUrl(string $url)
    {
        $this->line("Checking <comment>{$url}</comment>");
        $response = Http::withUserAgent(config('scan.useragent'))
            ->get($url);

        if (! $response->successful()) {
            return $this->line('<error>MISSING</error>');
        }

        $this->line('<info>FOUND</info>');
        $this->line('*** *** *** *** ***');
        $this->line($response->body());
        $this->line('*** *** *** *** ***');
    }
}
