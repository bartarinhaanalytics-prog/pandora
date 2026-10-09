/*
 * رندر فریم‌به‌فریم صحنهٔ کرسی و ساخت ویدیوهای حلقه‌ای پس‌زمینه.
 * پیش‌نیاز: `npm run dev -- --port 5199` در حال اجرا، Playwright با Chromium، و ffmpeg.
 * استفاده: node scripts/korsi/render-frames.cjs [shot ...]
 */
const { chromium } = require('playwright');
const { execFileSync } = require('child_process');
const fs = require('fs');
const os = require('os');
const path = require('path');

const FPS = 24;
const PERIOD = 8; // باید با PERIOD در korsi-scene.js یکی باشد تا حلقه بی‌درز بماند
const SHOTS = {
  hero: [1280, 720],
  heroPortrait: [720, 1280],
  quilt: [1280, 720],
  window: [1280, 720],
  wide: [1280, 720]
};
const OUT = path.resolve(__dirname, '../../public/video');

async function frames(shot, w, h, dir) {
  const b = await chromium.launch({ args: ['--use-gl=angle', '--use-angle=swiftshader', '--enable-unsafe-swiftshader'] });
  const p = await b.newPage({ viewport: { width: w, height: h } });
  await p.goto(`http://localhost:5199/scripts/korsi/render.html?shot=${shot}&w=${w}&h=${h}`);
  await p.waitForSelector('body[data-ready="1"]', { timeout: 60000 });
  for (let i = 0; i < FPS * PERIOD; i++) {
    const url = await p.evaluate((t) => {
      window.frame(t);
      return document.getElementById('c').toDataURL('image/png');
    }, i / FPS);
    fs.writeFileSync(`${dir}/${String(i).padStart(4, '0')}.png`, Buffer.from(url.split(',')[1], 'base64'));
  }
  await b.close();
}

function encode(shot, dir) {
  const input = ['-y', '-framerate', String(FPS), '-i', `${dir}/%04d.png`];
  execFileSync('ffmpeg', [...input, '-c:v', 'libx264', '-pix_fmt', 'yuv420p', '-crf', '27', '-preset', 'slow', '-tune', 'film', '-movflags', '+faststart', '-an', `${OUT}/${shot}.mp4`], { stdio: 'inherit' });
  execFileSync('ffmpeg', [...input, '-c:v', 'libvpx-vp9', '-pix_fmt', 'yuv420p', '-b:v', '0', '-crf', '38', '-row-mt', '1', '-an', `${OUT}/${shot}.webm`], { stdio: 'inherit' });
  execFileSync('ffmpeg', ['-y', '-i', `${dir}/0000.png`, '-c:v', 'libwebp', '-quality', '78', `${OUT}/${shot}.webp`], { stdio: 'inherit' });
}

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const list = process.argv.slice(2).length ? process.argv.slice(2) : Object.keys(SHOTS);
  for (const shot of list) {
    const dir = fs.mkdtempSync(path.join(os.tmpdir(), `korsi-${shot}-`));
    await frames(shot, ...SHOTS[shot], dir);
    encode(shot, dir);
    fs.rmSync(dir, { recursive: true });
  }
})();
