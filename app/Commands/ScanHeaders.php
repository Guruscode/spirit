<?php

namespace App\Commands;

use App\Traits\Domains;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

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

        $response = Cache::remember(
            __METHOD__.':'.$domain,
            now()->addHour(),
            fn () => Http::withUserAgent(config('scan.useragent'))->get($domain)
        );

        $redirectedTo = (string) $response->effectiveUri();

        if ($domain !== $redirectedTo) {
            $this->line("Redirected to <comment>{$redirectedTo}</comment>");
        }

        $headers = collect($response->headers())
            ->mapWithKeys(fn ($value, $key) => [strtolower($key) => $value])
            ->sortBy(fn ($value, $key) => $key);

        $maxKey = $headers->keys()->max(fn ($key) => strlen($key));

        $this->line('');

        $headers->each(
            fn ($value, $key) => $this->line('<comment>'.Str::padRight($key, $maxKey).'</comment> '.implode(PHP_EOL, $value))
        );

        $this->line('');
        $this->line("Check: https://securityheaders.com/?hide=on&followRedirects=on&q={$domain}");

        if (isset($headers['set-cookie'])) {
            $cookie = Str::of(implode(PHP_EOL, $headers['set-cookie']))->lower();
            $this->line('Secure cookies:       '.($cookie->contains('secure') ? '✅ YES!' : '❌ NOPE!'));
            $this->line('HttpOnly cookies:     '.($cookie->contains('httponly') ? '✅ YES!' : '❌ NOPE!'));
            $this->line('SameSite=Lax cookies: '.($cookie->contains('samesite=lax') ? '✅ YES!' : '❌ NOPE!'));
        }

        $this->line('');
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
