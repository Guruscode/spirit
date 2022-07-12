<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command as BaseCommand;
use Symfony\Component\Process\Process;

abstract class Command extends BaseCommand
{
    protected ?string $path;

    protected function header()
    {
        $title = "Spirit -> {$this->name}";
        $size = strlen($title);
        $spaces = str_repeat(' ', $size);

        $this->output->newLine();
        $this->output->writeln("<bg=blue;fg=white>    $spaces    </>");
        $this->output->writeln("<bg=blue;fg=white>    $title    </>");
        $this->output->writeln("<bg=blue;fg=white>    $spaces    </>");
        $this->output->newLine();
    }

    protected function bin(string $binary, string $command, ?string $path = null)
    {
        return $this->cmd($command, $binary, $path);
    }

    protected function cmd(string $command, ?string $spiritBinary = null, ?string $path = null)
    {
        if ($this->hasOption('path')) {
            $path ??= $this->path ?? $this->option('path') ?? getcwd();
        }
        if ($this->hasArgument('path')) {
            $path ??= $this->path ?? $this->argument('path') ?? getcwd();
        }

        $command = ($spiritBinary ? base_path($spiritBinary).' ' : '').$command;

        $this->line("<comment>{$path}</comment>$ {$command}");

        return tap(Process::fromShellCommandline($command, $path, timeout: 6000))
            ->run()
            ->getOutput();
    }
}
