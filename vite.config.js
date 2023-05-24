import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import path from 'path'
import postcssImport from 'postcss-import';
import autoprefixer from 'autoprefixer';
import tailwindcss from 'tailwindcss';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js'],
            refresh: [
                ...refreshPaths,
                'app/Http/Livewire/**',
                'app/Tables/Columns/**'
            ],
        }),
    ],
    resolve: {
        alias: {
            '~filament': path.resolve(__dirname, 'vendor/filament/filament'),
        }
    },
    css: {
        postcss: {
            plugins: [
                postcssImport(),
                tailwindcss(),
                autoprefixer(),
            ],
        },
    },

});
