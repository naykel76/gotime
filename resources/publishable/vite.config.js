import { defineConfig, loadEnv } from 'vite';
import laravel from "laravel-vite-plugin";
import purge from "@erbelion/vite-plugin-laravel-purgecss";

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '')
    const isDev = env.VITE_APP_ENV === 'local';
    const VITE_APP_URL = env.VITE_APP_URL;
    
    return {
        server: isDev ? {
            open: VITE_APP_URL,
            // Watch settings to ignore unnecessary directories during the dev server reload
            watch: {
                ignored: ['**/node_modules/**', '**/storage/**', '**/vendor/**'],
            },
            port: 5187,
        } : undefined, // In production, we don't define a server

        plugins: [
            laravel({
                input: isDev
                    ? ['resources/js/app.js']
                    : ['resources/js/app.js', 'resources/scss/app.scss'],
                refresh: true,
            }),

            // Custom plugin for handling Blade and Livewire files for full reload on updates
            {
                name: 'blade',
                handleHotUpdate({ file, server }) {
                    // Trigger full page reload for specific files like Blade and Livewire components
                    if (file.endsWith('.blade.php') || file.endsWith('.js') || (file.endsWith('.php') && file.includes('/app/Livewire/'))) {
                        server.ws.send({
                            type: 'full-reload',
                            path: '*',
                        });
                    }
                },
            },

            // PurgeCSS plugin for removing unused CSS in production builds
            purge({
                paths: [
                    // Path to Blade templates in views directory
                    'resources/views/**/*.blade.php',
                    // Include views in vendor packages
                    'vendor/naykel/**/resources/views/**/*.blade.php'
                ],
                safelist: {
                    // Prevent PurgeCSS from removing specific standard CSS selectors
                    standard: [/^\:has$/, /^\:is$/, /^\:not$/, /^\:where$/, /^\:disabled$/],
                    // Keep any classes that match these patterns in the final build
                    greedy: [/code$/, /hljs-/]
                },
                extractors: [
                    {
                        // Custom extractor to scan content and find valid CSS class names
                        extractor: (content) => {
                            return content.match(/[A-Za-z0-9-_:\/]+/g) || []
                        },
                        extensions: ['css', 'html', 'vue', 'php'],
                    },
                ],
            })
        ],
    }
});
