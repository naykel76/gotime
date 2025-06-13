import { defineConfig, loadEnv } from 'vite';
import laravel from "laravel-vite-plugin";
import purge from "@erbelion/vite-plugin-laravel-purgecss";

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '')
    const isDev = mode === 'development';
    const isStaging = mode === 'staging';
    const VITE_APP_URL = env.VITE_APP_URL;

    return {
        ...(isDev && {
            server: {
                open: VITE_APP_URL,
                watch: {
                    ignored: ['**/node_modules/**', '**/storage/**', '**/vendor/**', '!**/vendor/naykel/**'],
                },
                port: 5187
            },
        }),
        plugins: [
            laravel({
                input: ['resources/scss/app.scss', 'resources/js/app.js'],
                refresh: true
            }),
            // purge({
            //     paths: [
            //         'resources/views/**/*.blade.php',
            //         'vendor/naykel/**/*.php'
            //     ],
            //     safelist: {
            //         standard: [/^\:has$/, /^\:is$/, /^\:not$/, /^\:where$/, /^\:hover$/, /^\:disabled$/, /^\:focus$/, /^\:active$/],
            //     },
            //     extractors: [
            //         {
            //             extractor: (content) => {
            //                 return content.match(/[A-Za-z0-9-_:\/]+/g) || []
            //             },
            //             extensions: ['css', 'html', 'vue', 'php']
            //         },
            //     ],
            // })
        ],
    }
});