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
                "alpinejs" => "^3.10.2",
                "nk_jtb" => "file:../nk_jtb",
                "sass" => "1.53.0",
            ] + $packages;
        });

        // Public...
        (new Filesystem)->ensureDirectoryExists(public_path('images'));
        (new Filesystem)->ensureDirectoryExists(public_path('svg'));
        copy(__DIR__ . '/../../stubs/public/images/nk/logo.svg', public_path('images/logo.svg'));
        copy(__DIR__ . '/../../stubs/public/images/nk/favicon.ico', public_path('favicon.ico'));

        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/public/images', public_path('images'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/public/svg', public_path('svg'));

        // Resources...
        (new Filesystem)->ensureDirectoryExists(resource_path('js'));
        (new Filesystem)->ensureDirectoryExists(resource_path('navs'));
        (new Filesystem)->ensureDirectoryExists(resource_path('scss'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views'));

        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/resources/js', resource_path('js'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/resources/navs', resource_path('navs'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/resources/scss', resource_path('scss'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/resources/views', resource_path('views'));

        // Config...
        copy(__DIR__ . '/../../stubs/.env.example', base_path('.env.example'));
        copy(__DIR__ . '/../../stubs/vite.config.js', base_path('vite.config.js'));

        // Routes...
        copy(__DIR__ . '/../../stubs/routes.php', base_path('routes/web.php'));

        $this->info('Gotime scaffolding installed successfully.');
        $this->comment('Please execute the "npm install && npm run dev" command to build your assets.');

        return Command::SUCCESS;
    }

    /**
     * Update the "package.json" file.
     */
    protected static function updateNodePackages(callable $callback, $dev = true)
    {

        if (!file_exists(base_path('package.json'))) {
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
}
