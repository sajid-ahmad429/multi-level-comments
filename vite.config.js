import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    
    server: {
        cors: true,
        hmr: {
            host: 'localhost',
        },
    },

    build: {
        // Target modern browsers for better performance
        target: 'es2020',
        
        // Enable minification
        minify: 'terser',
        
        // Optimize terser settings
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
                pure_funcs: ['console.log', 'console.info'],
            },
            mangle: {
                safari10: true,
            },
        },

        // Optimize chunk splitting
        rollupOptions: {
            output: {
                manualChunks: {
                    // Vendor chunks for better caching
                    vendor: ['axios'],
                    // Livewire chunk
                    livewire: ['@livewire/navigate'],
                },
                // Optimize chunk naming for caching
                chunkFileNames: 'assets/js/[name]-[hash].js',
                entryFileNames: 'assets/js/[name]-[hash].js',
                assetFileNames: (assetInfo) => {
                    const extType = assetInfo.name.split('.').pop();
                    if (/png|jpe?g|svg|gif|tiff|bmp|ico/i.test(extType)) {
                        return 'assets/images/[name]-[hash][extname]';
                    }
                    if (/css/i.test(extType)) {
                        return 'assets/css/[name]-[hash][extname]';
                    }
                    return 'assets/[name]-[hash][extname]';
                },
            },
        },

        // Set chunk size warning limit
        chunkSizeWarningLimit: 1000,

        // Enable source maps for debugging (disable in production)
        sourcemap: process.env.NODE_ENV !== 'production',

        // Optimize CSS
        cssCodeSplit: true,
        cssMinify: true,

        // Asset optimization
        assetsInlineLimit: 4096, // 4kb
    },

    // Optimize dependencies
    optimizeDeps: {
        include: [
            'axios',
            '@livewire/navigate'
        ],
        // Force optimize these dependencies
        force: false,
    },

    // Define aliases for better imports
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
            '@css': resolve(__dirname, 'resources/css'),
        },
    },

    // Enable CSS preprocessing optimizations
    css: {
        devSourcemap: process.env.NODE_ENV !== 'production',
        preprocessorOptions: {
            scss: {
                // Add any SCSS optimizations here
            },
        },
    },

    // Performance optimizations
    esbuild: {
        // Remove console logs in production
        drop: process.env.NODE_ENV === 'production' ? ['console', 'debugger'] : [],
        // Enable legal comments removal
        legalComments: 'none',
    },

    // Preview server optimizations
    preview: {
        port: 4173,
        strictPort: true,
        cors: true,
    },
});