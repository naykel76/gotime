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
                // "@erbelion/vite-plugin-laravel-purgecss" => "^0.2.1",
                "@erbelion/vite-plugin-laravel-purgecss" => "github:naykel76/vite-plugin-laravel-purgecss",
                "@fullhuman/postcss-purgecss" => "^5.0.0",
                "nk_jtb" => "^0.9.1",
                "sass" => "1.60.0",
                'autoprefixer' => '^10.4.7',
                'postcss' => '^8.4.14',
            ] + $packages;
        });

        // Update node modules...
        if (!$this->stringInFile('./package.json', "\"type\": \"module\"")) {
            $this->replaceInFile(
                "\"private\": true,",
                "\"private\": true,
    \"type\": \"module\", ",
                './package.json'
            );
        }

        // Public...
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/public/', public_path());

        // Resources...
        (new Filesystem)->ensureDirectoryExists(resource_path('navs'));
        (new Filesystem)->ensureDirectoryExists(resource_path('scss'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views'));

        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/resources/navs', resource_path('navs'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/resources/scss', resource_path('scss'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/resources/views', resource_path('views'));

        // Config...
        copy(__DIR__ . '/../../stubs/.env.example', base_path('.env.example'));
        copy(__DIR__ . '/../../stubs/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__ . '/../../stubs/vite.config.js', base_path('vite.config.js'));
        copy(__DIR__ . '/../../stubs/readme.md', base_path('readme.md')); // updates the laravel readme.md

        // Routes...
        copy(__DIR__ . '/../../stubs/routes.php', base_path('routes/web.php'));

        $this->info('Gotime scaffolding installed successfully.');
        $this->comment('Please execute the "npm install && npm run dev" command to build your assets.');

        // Update app name...
        if (!$this->stringInFile('./.env', "APP_NAME=Naykel")) {
            $this->replaceInFile(
                "APP_NAME=Laravel",
                "APP_NAME=Naykel",
                './.env'
            );
        }

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

    /**
     * Replace a given string within a given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    /**
     * A given string exists within a given file.
     *
     * @param string $path
     * @param string $search
     * @return bool
     */
    protected function stringInFile($path, $search)
    {
        return str_contains(file_get_contents($path), $search);
    }
}
