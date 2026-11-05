
import { defineConfig } from 'vite';
import inertia from '@inertiajs/vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import vuetify from 'vite-plugin-vuetify';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import { fileURLToPath, URL } from 'node:url';

export default defineConfig({
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
        },
    },

    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        vue(),
        vuetify({
            autoImport: true,
        }),
        inertia({
            ssr: false,
        }),
        tailwindcss(),
        wayfinder(),
    ],

    server: {
        host: '127.0.0.1',
        port: 5173,
        strictPort: true,
        hmr: {
            host: '127.0.0.1',
            port: 5173,
            protocol: 'ws',
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
