import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            hotFile: 'public/vendor/tables/tables.hot',
            buildDirectory: 'vendor/tables',
            input: ['resources/assets/scss/table.scss', 'resources/assets/js/table.js'],
            refresh: true,
        }),
    ],
});
