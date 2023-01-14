// vite.config.js
import { defineConfig } from 'vite'
import VitePluginBrowserSync from 'vite-plugin-browser-sync'
import dotenv from 'dotenv'

dotenv.config()

export default defineConfig({
  plugins: [
    VitePluginBrowserSync({
      mode: 'proxy',
      bs: {
        ui: {
          port: 8090,
        },
        host: process.env.PROXY_URL,
        proxy: process.env.PROXY_URL
      }
    })
  ],
})