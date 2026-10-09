/*
 * صحنهٔ سه‌بعدی «شب کرسی» برای رندر ویدیوهای پس‌زمینه.
 * این فایل در سایت بارگذاری نمی‌شود؛ فقط برای ساختن ویدیوها در scripts/korsi/render.html است.
 * همهٔ بافت‌ها (لحاف قلمکار، قالی، پشتی، دیوار، چوب، آسمان) در کد کشیده می‌شوند.
 * همهٔ حرکت‌ها با دورهٔ T تکرار می‌شوند تا ویدیو بی‌درز حلقه بزند.
 */
import * as THREE from 'three';
import { RoundedBoxGeometry } from 'three/examples/jsm/geometries/RoundedBoxGeometry.js';

export const PERIOD = 8; // ثانیه

const C = {
  madder: '#7a1d22',
  madderDeep: '#5a1218',
  indigo: '#1b2350',
  indigoDeep: '#11163a',
  saffron: '#e0a03a',
  cotton: '#efe2c6',
  walnut: '#4a2c1a',
  brass: '#b8893a'
};

function canvas(w, h) {
  const c = document.createElement('canvas');
  c.width = w;
  c.height = h;
  return [c, c.getContext('2d')];
}

function rng(seed) {
  let x = seed;
  return () => {
    x = (x * 16807) % 2147483647;
    return (x - 1) / 2147483646;
  };
}

function tex(c, repeat = 1) {
  const t = new THREE.CanvasTexture(c);
  t.colorSpace = THREE.SRGBColorSpace;
  t.wrapS = t.wrapT = THREE.RepeatWrapping;
  t.repeat.set(repeat, repeat);
  t.anisotropy = 8;
  return t;
}

/** بته‌جقه: قطره‌ای با سر خمیده */
function boteh(g, x, y, s, rot, fill, line) {
  g.save();
  g.translate(x, y);
  g.rotate(rot);
  g.scale(s, s);
  g.beginPath();
  g.moveTo(0, 30);
  g.bezierCurveTo(-26, 22, -24, -14, 0, -22);
  g.bezierCurveTo(16, -28, 22, -40, 10, -46);
  g.bezierCurveTo(28, -40, 30, -10, 20, 8);
  g.bezierCurveTo(14, 22, 8, 30, 0, 30);
  g.closePath();
  g.fillStyle = fill;
  g.fill();
  g.lineWidth = 2.2;
  g.strokeStyle = line;
  g.stroke();
  // نقش درونی
  g.beginPath();
  g.moveTo(0, 18);
  g.bezierCurveTo(-12, 12, -10, -6, 2, -10);
  g.bezierCurveTo(10, -12, 12, -22, 8, -28);
  g.lineWidth = 2;
  g.stroke();
  g.fillStyle = line;
  for (let i = 0; i < 4; i += 1) {
    g.beginPath();
    g.arc(-4 + i * 2, 6 - i * 7, 1.8, 0, Math.PI * 2);
    g.fill();
  }
  g.restore();
}

function quiltTexture() {
  const [c, g] = canvas(2048, 2048);
  const r = rng(5);
  g.fillStyle = C.madder;
  g.fillRect(0, 0, 2048, 2048);
  // حاشیه‌های تودرتو
  const bands = [
    [0, 120, C.indigo],
    [120, 22, C.saffron],
    [142, 90, C.madderDeep],
    [232, 14, C.cotton],
    [246, 70, C.indigo]
  ];
  bands.forEach(([o, w, col]) => {
    g.fillStyle = col;
    g.fillRect(o, o, 2048 - o * 2, w);
    g.fillRect(o, 2048 - o - w, 2048 - o * 2, w);
    g.fillRect(o, o, w, 2048 - o * 2);
    g.fillRect(2048 - o - w, o, w, 2048 - o * 2);
  });
  // بته‌جقه‌های کوچک روی حاشیهٔ نیلی بیرونی
  for (let i = 0; i < 26; i += 1) {
    const p = 80 + i * 76;
    boteh(g, p, 60, 0.9, Math.PI / 2, C.saffron, C.indigoDeep);
    boteh(g, p, 1988, 0.9, -Math.PI / 2, C.saffron, C.indigoDeep);
    boteh(g, 60, p, 0.9, 0, C.saffron, C.indigoDeep);
    boteh(g, 1988, p, 0.9, Math.PI, C.saffron, C.indigoDeep);
  }
  // دوخت‌های حاشیه
  g.setLineDash([10, 8]);
  g.strokeStyle = 'rgba(239,226,198,0.7)';
  g.lineWidth = 3;
  g.strokeRect(300, 300, 1448, 1448);
  g.setLineDash([]);
  // متن میانی: ترنج
  g.save();
  g.translate(1024, 1024);
  for (let k = 0; k < 16; k += 1) {
    g.rotate((Math.PI * 2) / 16);
    boteh(g, 0, -300, 2.2, 0, k % 2 ? C.indigo : C.saffron, C.cotton);
  }
  g.beginPath();
  g.arc(0, 0, 150, 0, Math.PI * 2);
  g.fillStyle = C.indigo;
  g.fill();
  g.lineWidth = 10;
  g.strokeStyle = C.saffron;
  g.stroke();
  for (let k = 0; k < 8; k += 1) {
    g.rotate(Math.PI / 4);
    boteh(g, 0, -80, 1.1, 0, C.cotton, C.madderDeep);
  }
  g.restore();
  // پراکندهٔ بته‌جقه در زمینه
  for (let i = 0; i < 90; i += 1) {
    const x = 380 + r() * 1290;
    const y = 380 + r() * 1290;
    if (Math.hypot(x - 1024, y - 1024) < 470) continue;
    boteh(g, x, y, 0.8 + r() * 0.4, r() * Math.PI * 2, r() > 0.5 ? C.saffron : C.indigo, C.cotton);
  }
  // بافت پارچه
  const img = g.getImageData(0, 0, 2048, 2048);
  for (let i = 0; i < img.data.length; i += 4) {
    const n = (r() - 0.5) * 18;
    img.data[i] += n;
    img.data[i + 1] += n;
    img.data[i + 2] += n;
  }
  g.putImageData(img, 0, 0);
  return tex(c);
}

function rugTexture() {
  const [c, g] = canvas(1024, 768);
  g.fillStyle = '#5b1418';
  g.fillRect(0, 0, 1024, 768);
  g.fillStyle = C.indigoDeep;
  g.fillRect(0, 0, 1024, 70);
  g.fillRect(0, 698, 1024, 70);
  g.fillRect(0, 0, 70, 768);
  g.fillRect(954, 0, 70, 768);
  g.strokeStyle = '#c9893a';
  g.lineWidth = 6;
  g.strokeRect(84, 84, 856, 600);
  g.save();
  g.translate(512, 384);
  g.scale(1.6, 1);
  g.beginPath();
  for (let a = 0; a <= Math.PI * 2 + 0.01; a += Math.PI / 8) {
    const rr = a % (Math.PI / 4) < 0.01 ? 170 : 130;
    g.lineTo(Math.cos(a) * rr, Math.sin(a) * rr);
  }
  g.fillStyle = C.indigo;
  g.fill();
  g.restore();
  for (let i = 0; i < 14; i += 1) boteh(g, 140 + i * 56, 40, 0.5, Math.PI / 2, '#c9893a', C.indigoDeep);
  for (let i = 0; i < 14; i += 1) boteh(g, 140 + i * 56, 728, 0.5, -Math.PI / 2, '#c9893a', C.indigoDeep);
  return tex(c);
}

function kilimTexture(base, a, b) {
  const [c, g] = canvas(512, 512);
  g.fillStyle = base;
  g.fillRect(0, 0, 512, 512);
  for (let y = 0; y < 512; y += 64) {
    g.fillStyle = a;
    g.fillRect(0, y + 20, 512, 10);
    g.fillStyle = b;
    for (let x = 0; x < 512; x += 32) {
      g.beginPath();
      g.moveTo(x, y + 44);
      g.lineTo(x + 16, y + 34);
      g.lineTo(x + 32, y + 44);
      g.lineTo(x + 16, y + 54);
      g.closePath();
      g.fill();
    }
  }
  return tex(c, 1);
}

function plasterTexture() {
  const [c, g] = canvas(512, 512);
  const r = rng(9);
  g.fillStyle = '#6a4e3e';
  g.fillRect(0, 0, 512, 512);
  for (let i = 0; i < 2600; i += 1) {
    g.fillStyle = `rgba(${r() > 0.5 ? '255,235,210' : '30,18,12'},${0.04 + r() * 0.05})`;
    g.fillRect(r() * 512, r() * 512, 2 + r() * 6, 2 + r() * 6);
  }
  return tex(c, 3);
}

function woodTexture(base = C.walnut) {
  const [c, g] = canvas(512, 512);
  const r = rng(13);
  g.fillStyle = base;
  g.fillRect(0, 0, 512, 512);
  for (let y = 0; y < 512; y += 2) {
    g.fillStyle = `rgba(${r() > 0.5 ? '255,210,160' : '0,0,0'},${0.03 + r() * 0.05})`;
    g.fillRect(0, y, 512, 1 + r() * 2);
  }
  for (let y = 0; y < 512; y += 64) {
    g.fillStyle = 'rgba(0,0,0,0.35)';
    g.fillRect(0, y, 512, 2);
  }
  return tex(c, 4);
}

function nightTexture() {
  const [c, g] = canvas(1536, 1024);
  const grad = g.createLinearGradient(0, 0, 0, 1024);
  grad.addColorStop(0, '#0b1230');
  grad.addColorStop(0.6, '#1d2c5c');
  grad.addColorStop(1, '#2b3c6e');
  g.fillStyle = grad;
  g.fillRect(0, 0, 1536, 1024);
  const r = rng(21);
  for (let i = 0; i < 220; i += 1) {
    g.fillStyle = `rgba(232,238,251,${0.3 + r() * 0.6})`;
    g.beginPath();
    g.arc(r() * 1536, r() * 600, 0.6 + r() * 1.4, 0, Math.PI * 2);
    g.fill();
  }
  // ماه
  const mg = g.createRadialGradient(1050, 470, 0, 1050, 470, 220);
  mg.addColorStop(0, 'rgba(255,240,205,0.55)');
  mg.addColorStop(1, 'rgba(255,240,205,0)');
  g.fillStyle = mg;
  g.fillRect(800, 230, 500, 500);
  g.fillStyle = '#fff3d6';
  g.beginPath();
  g.arc(1050, 470, 70, 0, Math.PI * 2);
  g.fill();
  // بام‌ها و گنبد دوردست زیر برف
  g.fillStyle = '#121a36';
  g.beginPath();
  g.moveTo(0, 1024);
  g.lineTo(0, 780);
  const roofs = [[0, 780], [180, 780], [180, 730], [420, 730], [420, 800], [560, 800], [560, 700], [640, 700]];
  roofs.forEach(([x, y]) => g.lineTo(x, y));
  g.arc(760, 700, 120, Math.PI, 0);
  [[880, 700], [960, 700], [960, 760], [1200, 760], [1200, 720], [1536, 720], [1536, 1024]].forEach(([x, y]) => g.lineTo(x, y));
  g.closePath();
  g.fill();
  g.fillStyle = '#dfe6f5';
  [[0, 780, 180], [180, 730, 240], [420, 800, 140], [560, 700, 80], [880, 700, 80], [960, 760, 240], [1200, 720, 336]].forEach(([x, y, w]) => g.fillRect(x, y - 6, w, 8));
  g.beginPath();
  g.arc(760, 700, 122, Math.PI * 1.1, Math.PI * 1.9);
  g.lineWidth = 8;
  g.strokeStyle = '#dfe6f5';
  g.stroke();
  // پنجره‌های روشن دوردست
  g.fillStyle = 'rgba(255,196,110,0.85)';
  [[220, 760], [300, 760], [1000, 790], [1080, 790], [1300, 750], [1390, 750]].forEach(([x, y]) => g.fillRect(x, y, 16, 22));
  const t = tex(c);
  t.wrapS = t.wrapT = THREE.ClampToEdgeWrapping;
  return t;
}

function dotTexture() {
  const [c, g] = canvas(64, 64);
  const grad = g.createRadialGradient(32, 32, 0, 32, 32, 32);
  grad.addColorStop(0, 'rgba(255,255,255,1)');
  grad.addColorStop(0.4, 'rgba(255,255,255,0.6)');
  grad.addColorStop(1, 'rgba(255,255,255,0)');
  g.fillStyle = grad;
  g.fillRect(0, 0, 64, 64);
  return new THREE.CanvasTexture(c);
}

/** لحاف کرسی: صفحه‌ای که روی میز می‌افتد و روی زمین پهن می‌شود، با چین‌های نرم */
function quiltGeometry() {
  const S = 3.6;
  const geo = new THREE.PlaneGeometry(S, S, 160, 160);
  geo.rotateX(-Math.PI / 2);
  const p = geo.attributes.position;
  const top = 0.62; // نصف عرض روی میز
  const h = 0.5;
  for (let i = 0; i < p.count; i += 1) {
    const x = p.getX(i);
    const z = p.getZ(i);
    const d = Math.max(Math.abs(x), Math.abs(z)) - top; // فاصله از لبهٔ میز
    let y;
    let push = 0;
    if (d <= 0) {
      y = h + 0.02 * Math.sin(x * 6) * Math.sin(z * 5) - 0.015 * (1 - Math.max(Math.abs(x), Math.abs(z)) / top);
    } else {
      const fall = Math.min(1, d / 0.5);
      y = h * (1 - Math.pow(fall, 1.6)) + 0.01;
      push = d * 0.12;
      const ang = Math.atan2(z, x);
      const fold = Math.sin(ang * 22 + d * 3) * 0.035 * Math.min(1, d * 1.6);
      y = Math.max(0.006, y + fold * (fall < 1 ? 1 : 0.4));
    }
    const len = Math.hypot(x, z) || 1;
    p.setX(i, x + (x / len) * push);
    p.setZ(i, z + (z / len) * push);
    p.setY(i, y);
  }
  geo.computeVertexNormals();
  return geo;
}

function lathe(points, segs = 48) {
  return new THREE.LatheGeometry(points.map(([x, y]) => new THREE.Vector2(x, y)), segs);
}

export function buildKorsi(renderer) {
  renderer.shadowMap.enabled = true;
  renderer.shadowMap.type = THREE.PCFSoftShadowMap;
  renderer.outputColorSpace = THREE.SRGBColorSpace;
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1.15;

  const scene = new THREE.Scene();
  scene.background = new THREE.Color('#0d0a10');
  scene.fog = new THREE.Fog('#0d0a10', 7, 16);

  const std = (o) => new THREE.MeshStandardMaterial(o);

  // کف و قالی
  const floor = new THREE.Mesh(new THREE.PlaneGeometry(14, 14), std({ map: woodTexture('#3a2216'), roughness: 0.75 }));
  floor.rotation.x = -Math.PI / 2;
  floor.receiveShadow = true;
  scene.add(floor);
  const rug = new THREE.Mesh(new THREE.PlaneGeometry(5.4, 4.1), std({ map: rugTexture(), roughness: 0.95 }));
  rug.rotation.x = -Math.PI / 2;
  rug.position.y = 0.004;
  rug.receiveShadow = true;
  scene.add(rug);

  // دیوارها و پنجره
  const plaster = std({ map: plasterTexture(), roughness: 0.95 });
  const wallZ = -3;
  const winX = -1.1;
  const winY = 1.75;
  const winW = 1.5;
  const winH = 1.7;
  const addWall = (w, h, x, y, z, ry = 0) => {
    const m = new THREE.Mesh(new THREE.BoxGeometry(w, h, 0.25), plaster);
    m.position.set(x, y, z);
    m.rotation.y = ry;
    m.receiveShadow = true;
    m.castShadow = true;
    scene.add(m);
  };
  const W = 12;
  const H = 4.2;
  const left = winX - winW / 2;
  const right = winX + winW / 2;
  addWall(left + W / 2, H, (-W / 2 + left) / 2, H / 2, wallZ);
  addWall(W / 2 - right, H, (right + W / 2) / 2, H / 2, wallZ);
  addWall(winW, winY - winH / 2, winX, (winY - winH / 2) / 2, wallZ);
  addWall(winW, H - (winY + winH / 2), winX, (H + winY + winH / 2) / 2, wallZ);
  addWall(10, H, -4.2, H / 2, 1, Math.PI / 2);
  addWall(10, H, 4.6, H / 2, 1, Math.PI / 2);
  // طاقچه
  const niche = new THREE.Mesh(new THREE.BoxGeometry(0.9, 0.06, 0.32), std({ map: woodTexture(), roughness: 0.6 }));
  niche.position.set(1.6, 1.55, wallZ + 0.24);
  niche.castShadow = true;
  scene.add(niche);

  // قاب و شبکهٔ پنجره (گره‌چینی ساده)
  const wood = std({ map: woodTexture(), roughness: 0.55 });
  const bar = (w, h, x, y) => {
    const m = new THREE.Mesh(new THREE.BoxGeometry(w, h, 0.08), wood);
    m.position.set(x, y, wallZ + 0.02);
    m.castShadow = true;
    scene.add(m);
  };
  bar(winW + 0.12, 0.1, winX, winY + winH / 2);
  bar(winW + 0.12, 0.14, winX, winY - winH / 2);
  bar(0.1, winH, left, winY);
  bar(0.1, winH, right, winY);
  bar(0.05, winH, winX, winY);
  for (let k = 1; k < 4; k += 1) bar(winW, 0.04, winX, winY - winH / 2 + (winH / 4) * k);
  // آسمان بیرون
  const sky = new THREE.Mesh(new THREE.PlaneGeometry(9, 6), new THREE.MeshBasicMaterial({ map: nightTexture(), fog: false }));
  sky.position.set(winX - 2.2, 2.7, -6.5);
  scene.add(sky);

  // برف بیرون پنجره (تکرارشونده با دورهٔ PERIOD)
  const snowN = 700;
  const snowPos = new Float32Array(snowN * 3);
  const snowSeed = [];
  const sr = rng(31);
  for (let i = 0; i < snowN; i += 1) {
    snowSeed.push({ x: winX + (sr() - 0.5) * 4, z: -3.3 - sr() * 3, y0: sr() * 4, k: 1 + Math.floor(sr() * 2), sw: sr() * Math.PI * 2 });
  }
  const snowGeo = new THREE.BufferGeometry();
  snowGeo.setAttribute('position', new THREE.BufferAttribute(snowPos, 3));
  const snow = new THREE.Points(
    snowGeo,
    new THREE.PointsMaterial({ map: dotTexture(), size: 0.035, transparent: true, opacity: 0.9, depthWrite: false, color: '#e8eefc', fog: false })
  );
  scene.add(snow);

  // کرسی و لحاف
  const table = new THREE.Mesh(new THREE.BoxGeometry(1.2, 0.46, 1.2), std({ map: woodTexture(), roughness: 0.6 }));
  table.position.y = 0.23;
  scene.add(table);
  const quilt = new THREE.Mesh(quiltGeometry(), std({ map: quiltTexture(), roughness: 0.88, side: THREE.DoubleSide }));
  quilt.castShadow = true;
  quilt.receiveShadow = true;
  scene.add(quilt);

  // پشتی‌ها
  const kilimA = kilimTexture('#6b1a1d', '#e0a03a', '#1b2350');
  const kilimB = kilimTexture('#1b2350', '#c9893a', '#7a1d22');
  const cushion = (x, z, ry, mat) => {
    const m = new THREE.Mesh(new RoundedBoxGeometry(1.1, 0.55, 0.32, 6, 0.12), mat);
    m.position.set(x, 0.3, z);
    m.rotation.set(-0.18, ry, 0);
    m.castShadow = true;
    m.receiveShadow = true;
    scene.add(m);
  };
  const cmA = std({ map: kilimA, roughness: 0.92 });
  const cmB = std({ map: kilimB, roughness: 0.92 });
  cushion(-0.1, -2.3, 0, cmA);
  cushion(-2.0, -1.6, 0.9, cmB);
  cushion(-2.4, 0.2, Math.PI / 2, cmA);

  // سینی، انارها، استکان‌ها، کاسهٔ آجیل
  const topY = 0.5;
  const brass = std({ color: C.brass, metalness: 0.85, roughness: 0.32 });
  const tray = new THREE.Mesh(new THREE.CylinderGeometry(0.34, 0.32, 0.025, 64), brass);
  tray.position.set(0.12, topY + 0.012, 0.05);
  tray.castShadow = true;
  tray.receiveShadow = true;
  scene.add(tray);
  const pomMat = std({ color: '#b0222a', roughness: 0.38, metalness: 0.05 });
  const crownMat = std({ color: '#5e1015', roughness: 0.6 });
  const pom = (x, z, s) => {
    const g = new THREE.Group();
    const b = new THREE.Mesh(new THREE.SphereGeometry(0.075, 48, 32), pomMat);
    b.scale.set(1, 0.9, 1);
    const cr = new THREE.Mesh(new THREE.CylinderGeometry(0.018, 0.024, 0.03, 6, 1, true), crownMat);
    cr.position.y = 0.07;
    g.add(b, cr);
    g.position.set(x, topY + 0.03 + 0.068 * s, z);
    g.scale.setScalar(s);
    g.children.forEach((m) => {
      m.castShadow = true;
    });
    scene.add(g);
  };
  pom(0.05, 0.12, 1);
  pom(0.2, 0.0, 0.92);
  pom(-0.02, -0.06, 0.85);
  const glassMat = new THREE.MeshPhysicalMaterial({ color: '#ffe8c8', roughness: 0.05, transparent: true, opacity: 0.35, metalness: 0 });
  const teaMat = std({ color: '#8a3410', roughness: 0.2, emissive: '#2a0a00', emissiveIntensity: 0.4 });
  const tea = (x, z) => {
    const saucer = new THREE.Mesh(new THREE.CylinderGeometry(0.07, 0.05, 0.01, 32), std({ color: '#efe2c6', roughness: 0.3 }));
    saucer.position.set(x, topY + 0.03, z);
    const glass = new THREE.Mesh(lathe([[0.001, 0], [0.026, 0], [0.022, 0.03], [0.026, 0.06], [0.03, 0.085]], 32), glassMat);
    glass.position.set(x, topY + 0.035, z);
    const liquid = new THREE.Mesh(new THREE.CylinderGeometry(0.023, 0.024, 0.055, 24), teaMat);
    liquid.position.set(x, topY + 0.035 + 0.028, z);
    [saucer, liquid].forEach((m) => {
      m.castShadow = true;
    });
    scene.add(saucer, liquid, glass);
  };
  tea(0.42, 0.32);
  tea(-0.32, 0.38);

  // کتاب قصهٔ باز روی لبهٔ لحاف
  const pageMat = std({ color: '#efe2c6', roughness: 0.8 });
  const coverMat = std({ color: C.indigo, roughness: 0.7 });
  const book = new THREE.Group();
  const pageL = new THREE.Mesh(new THREE.BoxGeometry(0.22, 0.012, 0.3), pageMat);
  pageL.position.set(-0.115, 0.012, 0);
  pageL.rotation.z = 0.06;
  const pageR = pageL.clone();
  pageR.position.x = 0.115;
  pageR.rotation.z = -0.06;
  const cover = new THREE.Mesh(new THREE.BoxGeometry(0.47, 0.008, 0.31), coverMat);
  book.add(cover, pageL, pageR);
  book.position.set(-0.35, topY + 0.02, -0.25);
  book.rotation.y = 0.5;
  book.children.forEach((m) => {
    m.castShadow = true;
  });
  scene.add(book);

  // چراغ گردسوز
  const lamp = new THREE.Group();
  const lampBase = new THREE.Mesh(lathe([[0, 0], [0.09, 0], [0.1, 0.02], [0.07, 0.05], [0.08, 0.12], [0.05, 0.16], [0.03, 0.17]]), brass);
  const chimney = new THREE.Mesh(
    lathe([[0.03, 0], [0.06, 0.05], [0.065, 0.1], [0.04, 0.17], [0.032, 0.26]]),
    new THREE.MeshPhysicalMaterial({ color: '#fff4dc', roughness: 0.05, transparent: true, opacity: 0.28, side: THREE.DoubleSide })
  );
  chimney.position.y = 0.17;
  const flame = new THREE.Mesh(new THREE.SphereGeometry(0.022, 24, 16), new THREE.MeshBasicMaterial({ color: '#ffd27a' }));
  flame.scale.set(0.8, 1.8, 0.8);
  flame.position.y = 0.25;
  lamp.add(lampBase, chimney, flame);
  lamp.position.set(-0.3, topY + 0.02, 0.15);
  lampBase.castShadow = true;
  scene.add(lamp);

  // نور
  const lampLight = new THREE.PointLight('#ffb35c', 7, 9, 1.6);
  lampLight.position.set(-0.3, topY + 0.3, 0.15);
  lampLight.castShadow = true;
  lampLight.shadow.mapSize.set(2048, 2048);
  lampLight.shadow.bias = -0.002;
  lampLight.shadow.radius = 10;
  scene.add(lampLight);
  const underGlow = new THREE.PointLight('#ff8a3c', 2.2, 3.4, 2);
  underGlow.position.set(0.2, 0.12, 1.5);
  scene.add(underGlow);
  const sideGlow = new THREE.PointLight('#ff9a4c', 1.8, 3.2, 2);
  sideGlow.position.set(1.5, 0.15, 0.3);
  scene.add(sideGlow);
  const moon = new THREE.DirectionalLight('#8aa2e8', 1.9);
  moon.position.set(winX - 0.5, 4, -8);
  moon.target.position.set(0, 0, 0.5);
  moon.castShadow = true;
  moon.shadow.mapSize.set(2048, 2048);
  moon.shadow.camera.left = -4;
  moon.shadow.camera.right = 4;
  moon.shadow.camera.top = 4;
  moon.shadow.camera.bottom = -4;
  moon.shadow.radius = 4;
  scene.add(moon, moon.target);
  scene.add(new THREE.HemisphereLight('#3a4a8a', '#2a120c', 0.5));
  const fill = new THREE.DirectionalLight('#ffcf9a', 0.45);
  fill.position.set(3, 3, 4);
  scene.add(fill);

  const camera = new THREE.PerspectiveCamera(38, 16 / 9, 0.05, 40);

  /** t ثانیه از شروع؛ همه‌چیز با دورهٔ PERIOD تکرار می‌شود */
  function setTime(t) {
    const w = (Math.PI * 2) / PERIOD;
    // لرزش شعلهٔ چراغ: مجموع سینوس‌هایی با بسامد صحیح نسبت به دوره
    const f = 1 + 0.06 * Math.sin(w * 7 * t) + 0.04 * Math.sin(w * 13 * t + 1) + 0.03 * Math.sin(w * 23 * t + 2);
    lampLight.intensity = 7 * f;
    flame.scale.set(0.8, 1.8 * (0.94 + 0.06 * f), 0.8);
    underGlow.intensity = 1.6 * (1 + 0.05 * Math.sin(w * 3 * t));
    // برف
    for (let i = 0; i < snowN; i += 1) {
      const s = snowSeed[i];
      const y = ((s.y0 - (4 / PERIOD) * s.k * t) % 4 + 4) % 4;
      snowPos[i * 3] = s.x + Math.sin(w * t * s.k + s.sw) * 0.06;
      snowPos[i * 3 + 1] = y;
      snowPos[i * 3 + 2] = s.z;
    }
    snowGeo.attributes.position.needsUpdate = true;
  }

  return { scene, camera, setTime };
}

/** مسیرهای دوربین برای هر ویدیو؛ حرکت روی بیضی کوچک تا حلقه بی‌درز باشد */
export const SHOTS = {
  hero: { pos: [2.7, 1.5, 3.1], look: [-0.45, 0.62, -0.7], sway: [0.22, 0.05, 0.14], fov: 38 },
  heroPortrait: { pos: [1.5, 1.6, 3.6], look: [-0.35, 0.7, -0.6], sway: [0.15, 0.04, 0.1], fov: 52 },
  quilt: { pos: [0.95, 1.08, 1.05], look: [-0.12, 0.48, -0.08], sway: [0.07, 0.025, 0.05], fov: 40 },
  window: { pos: [0.6, 1.4, 0.4], look: [-1.15, 1.65, -3], sway: [0.08, 0.03, 0.05], fov: 40 },
  wide: { pos: [-2.6, 0.85, 2.7], look: [0.1, 0.62, -0.5], sway: [0.18, 0.04, 0.12], fov: 40 }
};

export function placeCamera(camera, shot, t) {
  const w = (Math.PI * 2) / PERIOD;
  const s = SHOTS[shot];
  camera.fov = s.fov;
  camera.position.set(s.pos[0] + s.sway[0] * Math.sin(w * t), s.pos[1] + s.sway[1] * Math.sin(w * 2 * t), s.pos[2] + s.sway[2] * Math.cos(w * t));
  camera.lookAt(s.look[0], s.look[1], s.look[2]);
  camera.updateProjectionMatrix();
}
