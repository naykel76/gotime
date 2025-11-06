<?php

namespace Naykel\Gotime\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'gotime:install';

    /**
     * The console command description.
     */
    protected $description = 'Install Gotime resources';

    public function handle()
    {
        $this->info('Installing Gotime...');

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@erbelion/vite-plugin-laravel-purgecss' => '^0.4.2',
                'autoprefixer' => '^10.4.21',
                'nk_jtb' => '^0.20.0',
                'postcss' => '^8.5.6',
                'sass' => '^1.93.3',
            ] + $packages;
        });

        // NPM Scripts...
        $this->updateNodeScripts(function ($scripts) {
            return [
                'build' => 'vite build',
                'debug' => 'vite --debug',
                'dev' => 'vite',
                'log' => 'code storage/logs/laravel.log',
                'nuke' => 'rm -rf node_modules vendor public/build',
                'nuke:ps' => 'powershell -NoProfile -Command "Remove-Item -Recurse -Force node_modules, vendor, public/build"',
            ] + $scripts;
        });

        File::copyDirectory(__DIR__ . '/../../stubs', base_path());
        copy(__DIR__ . '/../../pint.json', base_path('pint.json'));
        copy(__DIR__ . '/../../.gitignore', base_path('.gitignore'));

        // Clean up
        File::deleteDirectory(resource_path('css'));

        if (File::exists(resource_path('views/welcome.blade.php'))) {
            File::delete(resource_path('views/welcome.blade.php'));
        }

        $this->info('Gotime scaffolding installed successfully.');
        $this->comment('Please execute the "npm install && npm run dev" command to build your assets.');

        return Command::SUCCESS;
    }

    /**
     * Update the "package.json" file.
     */
    protected static function updateNodePackages(callable $callback, $dev = true)
    {

        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }

    /**
     * Update the "scripts" section of the package.json file.
     */
    protected static function updateNodeScripts(callable $callback)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages['scripts'] = $callback(
            array_key_exists('scripts', $packages) ? $packages['scripts'] : []
        );

        ksort($packages['scripts']);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }
}
