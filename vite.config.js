import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from 'tailwindcss';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
                // compilerOptions: {
                //     isCustomElement: (tag) => ['Link'].includes(tag),
                // }
            },
        }),
    ],
    css: {
        postcss: {
            plugins: [tailwindcss()],
        },
    }
});
