import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  base: './',
  plugins: [react()],
  build: {
    target: 'es2020',
    cssCodeSplit: true,
    rollupOptions: {
      output: {
        // three.js only ships in the lazily loaded hero scene chunk
        manualChunks: { three: ['three'] }
      }
    }
  }
});
