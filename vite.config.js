import { defineConfig } from 'vite';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/login.scss',
                'resources/sass/app.scss',
                'resources/js/login.js',
                'resources/js/app.js',
                'node_modules/chart.js/dist/Chart.min.js',
                'node_modules/chart.js/dist/Chart.min.css',
            ],
            refresh: true,
        }),

        viteStaticCopy({
            targets: [
                {
                    src: 'node_modules/@fortawesome/fontawesome-free/webfonts',
                    dest: '',
                },
            ],
        }),
    ],
});
