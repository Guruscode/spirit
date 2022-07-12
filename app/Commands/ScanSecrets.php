<?php

namespace App\Commands;

class ScanSecrets extends Command
{
    /** @var string */
    protected $signature = 'scan:secrets {path? : Local path to scan}';

    /** @var string */
    protected $description = 'Scans the path for secrets and keys that shouldn\'t be there.';

    public function handle()
    {
        $this->header();

        $this->cmd('rm trufflehog.txt gitleaks.json');
        $this->bin('trufflehog', 'filesystem --directory=./ > trufflehog.txt');
        $this->bin('gitleaks', 'detect --verbose > gitleaks.json');
    }
}
