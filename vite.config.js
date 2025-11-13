import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    // La propiedad 'base' resuelve el problema de las rutas en Render.
    // Usará VITE_ASSET_URL si está definida (en producción) o '/' por defecto.
    base: process.env.VITE_ASSET_URL || '/',
    
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // Configuraciones de servidor de desarrollo que puedes necesitar para el entorno local
    server: {
        host: '0.0.0.0', // Es bueno para el entorno Docker local
        hmr: {
            host: 'localhost',
        },
    },
});