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
                'nk_jtb' => '^0.22.0',
                'postcss' => '^8.5.6',
                'sass' => '^1.93.3',
            ] + $packages;
        });

        // NPM Dev Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@alpinejs/collapse' => '^3.15.4',
                '@alpinejs/sort' => '^3.15.4',
                'filepond' => '^4.32.10',
                'filepond-plugin-file-validate-size' => '^2.2.8',
                'filepond-plugin-file-validate-type' => '^1.2.9',
                'filepond-plugin-image-preview' => '^4.6.12',
            ] + $packages;
        }, false);

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

        // Remove tailwindcss packages...
        $this->removeNodePackages([
            '@tailwindcss/vite',
            'tailwindcss',
        ]);

        File::copyDirectory(__DIR__ . '/../../stubs', base_path());
        copy(__DIR__ . '/../../pint.json', base_path('pint.json'));

        // Add to .gitignore
        $gitignorePath = base_path('.gitignore');
        $gitignoreEntries = "\n/.agents\n/.cursor\n/.github\n/.vscode\n/tmp\nnk_tasks.md";
        File::append($gitignorePath, $gitignoreEntries);

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

    /**
     * Remove packages from the "package.json" file.
     */
    protected static function removeNodePackages(array $packagesToRemove)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        foreach (['dependencies', 'devDependencies'] as $key) {
            if (array_key_exists($key, $packages)) {
                foreach ($packagesToRemove as $package) {
                    unset($packages[$key][$package]);
                }
            }
        }

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }
}
