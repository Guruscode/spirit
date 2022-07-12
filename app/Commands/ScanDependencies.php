<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;

class ScanDependencies extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'scan:dependencies {path : Local path to scan}';
    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->header();

        $this->cmd('composer audit > composer-audit.txt');
        $this->bin('local-php-security-checker', '-format markdown > composer-security.md');
        $this->cmd('npm audit > npm-audit.txt');
    }
}

