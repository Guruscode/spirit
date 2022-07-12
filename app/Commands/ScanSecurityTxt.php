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

        $url = $domain.'/security.txt';
        $this->line("Checking <comment>{$url}</comment>");
        $response = Http::withUserAgent(config('scan.useragent'))
            ->get($url);

        $this->line($response->successful() ? '<info>FOUND</info>' : '<error>MISSING</error>');

        $url = $domain.'/.well-known/security.txt';
        $this->line("Checking <comment>{$url}</comment>");
        $response = Http::withUserAgent(config('scan.useragent'))
            ->get($url);

        $this->line($response->successful() ? '<info>FOUND</info>' : '<error>MISSING</error>');
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
