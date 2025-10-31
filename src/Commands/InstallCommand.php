<?php

namespace Naykel\Gotime\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gotime:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Gotime resources';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@erbelion/vite-plugin-laravel-purgecss' => '^0.4.2',
                'autoprefixer' => '^10.4.21',
                'nk_jtb' => '^0.15.0',
                'postcss' => '^8.5.6',
                'sass' => '^1.90.0',
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

        // Resources...
        (new Filesystem)->copyDirectory(__DIR__ . '/../../resources/publishable/resources/navs', resource_path('navs'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../resources/publishable/resources/scss', resource_path('scss'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../resources/publishable/resources/views', resource_path('views'));

        // NK::TD change this to a prompt so it is not installed each time
        copy(__DIR__ . '/../../config/markdown.php', base_path('config/markdown.php'));

        // Assets...
        copy(__DIR__ . '/../../resources/publishable/.env.example', base_path('.env.example'));
        copy(__DIR__ . '/../../resources/publishable/vite.config.js', base_path('vite.config.js'));
        copy(__DIR__ . '/../../resources/publishable/readme.md', base_path('readme.md'));
        copy(__DIR__ . '/../../pint.json', base_path('pint.json'));
        copy(__DIR__ . '/../../.gitignore', base_path('.gitignore'));

        // Public...
        (new Filesystem)->copyDirectory(__DIR__ . '/../../resources/publishable/public/', public_path());

        // Routes...
        copy(__DIR__ . '/../routes.php', base_path('routes/web.php'));

        $this->info('Gotime scaffolding installed successfully.');
        $this->comment('Please execute the "npm install && npm run dev" command to build your assets.');

        // clean up
        shell_exec('rm -rf ' . resource_path('css'));
        if (file_exists(resource_path('views/welcome.blade.php'))) unlink(resource_path('views/welcome.blade.php'));

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
