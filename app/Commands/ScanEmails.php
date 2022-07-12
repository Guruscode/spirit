<?php
namespace App\Commands;

use App\Traits\HIBP;
use Symfony\Component\Process\Process;

class ScanEmails extends Command
{
    use HIBP;

    /** @var string */
    protected $signature = 'scan:emails {path : path to scan}';

    /** @var string */
    protected $description = 'Finds all possible email addresses in the directory and checks their status with the HIBP API.';

    protected string $grep = 'grep -rEo';
    protected string $regex = '\\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,6}\\b';
    protected array $excludeDirs = ['vendor', 'node_modules', '.git'];
    protected array $excludeFiles = ['composer.lock'];

    public function handle()
    {
        $this->header();

        $path = $this->argument('path');
        $this->line("Checking <comment>{$path}</comment>");

        $command = "{$this->grep} \"{$this->regex}\" ";
        $command .= ' --exclude-dir='.implode(' --exclude-dir=', $this->excludeDirs);
        $command .= ' --exclude='.implode(' --exclude=', $this->excludeFiles);
        $command .= ' ./';

        $this->line('* Looking for email addresses...');
        $output = tap(Process::fromShellCommandline($command, $path, timeout: 600))->run()
            ->getOutput();
        $lines = explode(PHP_EOL, trim($output));

        $this->line('* Found <comment>'.count($lines).'</comment> possible matches...');
        $emails = collect($lines)
            ->reject(fn (string $value) => ! str_contains($value, ':') || (str_starts_with('Binary file ', $value) && str_ends_with(' matches', $value)))
            ->map(fn (string $value) => last(explode(':', $value)))
            ->unique()
            ->sort();

        $this->line("* Found <comment>{$emails->count()}</comment> unique emails...");

        $this->line('* Checking HIBP...');
        $this->output->progressStart($emails->count());
        $breached = $emails->filter(
            fn (string $email) => tap($this->pwned($email), fn () => $this->output->progressAdvance())
        );
        $this->output->progressFinish();

        if ($breached->isEmpty()) {
            $this->info('No breached emails found.');
            return;
        }

        $this->error("Found {$breached->count()} breached emails.");
        file_put_contents($path.'/pwned-emails.txt', $breached->implode(PHP_EOL));
        $this->line($breached->implode(', '));
    }
}
