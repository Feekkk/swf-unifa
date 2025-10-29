import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/variables.css',
                'resources/css/base.css',
                'resources/css/components.css',
                'resources/css/layout.css',
                'resources/js/app.js',
                'resources/js/auth.js',
                'resources/js/forms.js',
                'resources/js/navigation.js',
                'resources/js/slideshow.js',
                'resources/js/accessibility.js',
                'resources/js/lazy-loading.js',
                'resources/js/polyfills.js',
                'resources/js/utils.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['axios'],
                    accessibility: ['resources/js/accessibility.js'],
                    performance: ['resources/js/lazy-loading.js'],
                }
            }
        },
        cssCodeSplit: true,
        sourcemap: false,
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
        },
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
