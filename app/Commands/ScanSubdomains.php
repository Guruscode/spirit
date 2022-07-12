<?php
namespace App\Commands;

use App\Traits\Http;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Process\Process;

class ScanSubdomains extends Command
{
    use Http;

    /** @var string */
    protected $signature = 'scan:subdomains {domain}';

    /** @var string */
    protected $description = 'Scan the domain for any subdomains and check response codes.';

    public function handle()
    {
        $this->header();

        $domain = $this->argument('domain');
        $this->line("Scanning <comment>{$domain}</comment>");

        $this->line('* Looking for subdomains...');
        $subdomains = $this->findSubdomains($domain);
        $this->line("* Found <comment>{$subdomains->count()}</comment> subdomains...");

        $subdomains->each(function ($domain) {
            $this->line("* Checking <info>{$domain}</info>");
            try {
                $response = $this->http()
                    ->timeout(3)
                    ->get('http://'.$domain);
                $status = $response->successful() ? 'comment' : 'error';
                $this->line("  Status: <{$status}>{$response->status()}</{$status}> ({$response->effectiveUri()})");
            } catch (\Throwable $exception) {
                $this->error('  Failed to connect');
            }
        });
    }

    protected function findSubdomains(string $domain): Collection
    {
        return Cache::remember(__METHOD__."::{$domain}", now()->addWeek(), function () use ($domain) {
            $output = tap(Process::fromShellCommandline("./assetfinder {$domain}", base_path(), timeout: 6000))->run()
                ->getOutput();

            return new Collection(explode(PHP_EOL, trim($output)));
        });
    }
}
