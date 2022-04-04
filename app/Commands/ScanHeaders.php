<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

class ScanHeaders extends Command
{
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
     *
     * @return mixed
     */
    public function handle()
    {
        $domain = $this->argument('domain');

        if (! Str::startsWith($domain, 'http')) {
            $domain = "https://{$domain}";
        }

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
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
