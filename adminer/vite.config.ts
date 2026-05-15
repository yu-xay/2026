//@ts-nocheck
// import {fileURLToPath, URL} from 'node:url';
import {fileURLToPath} from 'url';

import {defineConfig} from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'
import tailwindcss from '@tailwindcss/vite'
// https://vite.dev/config/
export default defineConfig({
    plugins: [
        vue(),
        tailwindcss(),
        vueDevTools(),
    ],
    server: {
        port: 777,
        httpsx: {
            key: '/home/yu/app/yu/docker/nginx/cert/server.key',
            cert: '/home/yu/app/yu/docker/nginx/cert/server.crt'
        },
        proxy: {
            '/api': {
                target: 'http://api.localhost',
                changeOrigin: true,
                // secure: false,
                rewrite: (path) => path.replace(/^\/api/, '/api'),
                configure: (proxy, options) => {
                    proxy.on('proxyReq', (proxyReq, req, res) => {
                        console.log('🌐 代理请求:', req.method, req.url);
                    });
                    proxy.on('proxyRes', (proxyRes, req, res) => {
                        console.log('📥 HTTPS 响应:', proxyRes.statusCode);
                    });
                    proxy.on('error', (err, req, res) => {
                        console.log('❌ HTTPS 代理错误:', err.message);
                    });
                }
            },
        }
    },
    resolve: {
        // alias: {
        //     '@': path.resolve(__dirname, 'src')
        // },
        alias: {
            '@': fileURLToPath(new URL('./src', import.meta.url)),
        },
    },

    build: {
        rollupOptions: {
            output: {
                manualChunks(id: string) {
                    if (id.includes('node_modules')) {
                        let pkName = id.toString().split('node_modules/')[1].split('/')[0].toString();
                        if (['element-plus', 'echarts', 'zrender', '@element-plus'].includes(pkName)) {
                            return pkName;
                        } else {
                            return 'vendor';
                        }
                    }
                }
            }
        },
        chunkSizeWarningLimit: 1000,
    }
})
