// vite.config.js
import { defineConfig } from 'vite'
import FullReload from 'vite-plugin-full-reload'

import { readTemplatesDir } from './core/script/template'

const inputFiles = readTemplatesDir()

export default defineConfig({
  plugins: [
    FullReload([
      'src/**/*.{ts,tsx,js,css,scss}',
      'templates/**/*.php',
      'partials/**/*.php',
      './header.php',
      './footer.php',
    ])
  ],
  build: {
    rollupOptions: {
      input: {
        ...inputFiles
      }
    }
  }
})