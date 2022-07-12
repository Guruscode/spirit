<?php

namespace App\Commands;

use App\Traits\Domains;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Http;

class ScanHeaders extends Command
{
    use Domains;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'scan:headers {domain : Domain name to scan for headers}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Scan the response headers of the domain for security headers.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->header();
        $domain = $this->domain();

        $this->line("Scanning headers: <info>{$domain}</info>");

        $response = Http::withUserAgent(config('scan.useragent'))
            ->get($domain);

        $redirectedTo = (string) $response->effectiveUri();

        if ($domain !== $redirectedTo) {
            $this->line("Redirected to <comment>{$redirectedTo}</comment>");
        }

        $this->line("Check: https://securityheaders.com/?hide=on&followRedirects=on&q={$domain}");

        collect($response->headers())
            ->each(fn ($value, $key) => $this->line("<comment>{$key}</comment>\t".implode(PHP_EOL, $value)));
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
