import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/cupo-demanda.js',
                'resources/js/tipo-centro-salud.js',
                'resources/js/ciudad.js',
            ],
            refresh: true,
        }),
    ],
});
