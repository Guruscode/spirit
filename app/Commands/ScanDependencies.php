<?php

namespace App\Commands;

class ScanDependencies extends Command
{
    /** @var string */
    protected $signature = 'scan:dependencies {path? : Local path to scan}';

    /** @var string */
    protected $description = 'Scans PHP and NPM dependencies for known vulns, outdated packages, etc.';

    public function handle()
    {
        $this->header();

        $this->cmd('composer audit > composer-audit.txt');
        $this->cmd('composer outdated > composer-outdated.txt');
        $this->bin('local-php-security-checker', '-format markdown > composer-security.md');
        $this->cmd('npm audit > npm-audit.txt');
    }
}
