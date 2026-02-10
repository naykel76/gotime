<?php

namespace Naykel\Gotime\Commands;

use Illuminate\Console\Command;

class ConfigCommand extends Command
{
    protected $signature = 'gotime:config';

    protected $description = 'Publish Gotime config file';

    public function handle()
    {
        $this->call('vendor:publish', ['--tag' => 'gotime-config', '--force' => true]);
    }
}
