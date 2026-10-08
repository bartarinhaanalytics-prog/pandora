/*
 * صحنهٔ سه‌بعدی Hero: «گهوارهٔ ماه»
 * یک هلال ماه به شکل گهواره که مروارید (دُردانه) در آن آرام گرفته، در آسمان شب.
 * سه سطح عمق: ستاره‌ها (عقب)، گهواره و مروارید (کانون)، تپه‌ها (جلو).
 * قاب طاقی‌شکل صحنه با CSS ساخته شده تا بدون WebGL هم دیده شود.
 *
 * عملکرد:
 * - هندسه‌ها در کد ساخته می‌شوند؛ هیچ مدل یا تکسچری دانلود نمی‌شود.
 * - رندر فقط وقتی چیزی تغییر کند انجام می‌شود (render on demand)، حلقهٔ دائمی ندارد.
 * - بیرون از دید یا در تب غیرفعال، رندر متوقف است (کنترل از HeroScene.jsx).
 * - بدون سایهٔ بلادرنگ؛ نور نرم و جهت‌دار به‌همراه محیط بازتاب کم‌حجم.
 */
import {
  ACESFilmicToneMapping,
  BufferAttribute,
  BufferGeometry,
  Color,
  DirectionalLight,
  Group,
  HemisphereLight,
  Mesh,
  MeshBasicMaterial,
  MeshPhysicalMaterial,
  MeshStandardMaterial,
  PerspectiveCamera,
  PMREMGenerator,
  Points,
  PointsMaterial,
  Scene,
  Shape,
  ShapeGeometry,
  SphereGeometry,
  SRGBColorSpace,
  Vector3,
  WebGLRenderer
} from 'three';
import { RoomEnvironment } from 'three/examples/jsm/environments/RoomEnvironment.js';

const COLORS = {
  hillFar: '#163f47',
  hillNear: '#0f2f38',
  moon: '#e8b64a',
  pearl: '#efe7dc',
  pearlSheen: '#f3c9c0',
  star: '#eef1f6',
  skyLight: '#c9d6ff',
  groundLight: '#0f3f42'
};

/** هلال گهواره: لوله‌ای روی یک کمان که دو سرش باریک می‌شود. */
function createCrescentGeometry({ radius = 1.25, thickness = 0.34, arcStart, arcEnd, tubular = 96, radial = 32 }) {
  const positions = [];
  const normals = [];
  const indices = [];
  const center = new Vector3();
  const tangent = new Vector3();
  const nrm = new Vector3();
  const bin = new Vector3(0, 0, 1);

  for (let i = 0; i <= tubular; i += 1) {
    const t = i / tubular;
    const a = arcStart + (arcEnd - arcStart) * t;
    center.set(Math.cos(a) * radius, Math.sin(a) * radius, 0);
    tangent.set(-Math.sin(a), Math.cos(a), 0);
    nrm.set(Math.cos(a), Math.sin(a), 0);
    // باریک شدن نرم دو سر هلال
    const r = thickness * Math.pow(Math.sin(Math.PI * t), 0.75) + 0.012;
    for (let j = 0; j <= radial; j += 1) {
      const v = (j / radial) * Math.PI * 2;
      const cx = Math.cos(v);
      const cy = Math.sin(v);
      const nx = nrm.x * cx + bin.x * cy;
      const ny = nrm.y * cx + bin.y * cy;
      const nz = nrm.z * cx + bin.z * cy;
      positions.push(center.x + r * nx, center.y + r * ny, center.z + r * nz);
      normals.push(nx, ny, nz);
    }
  }
  for (let i = 0; i < tubular; i += 1) {
    for (let j = 0; j < radial; j += 1) {
      const a = (radial + 1) * i + j;
      const b = (radial + 1) * (i + 1) + j;
      indices.push(a, b, a + 1, b, b + 1, a + 1);
    }
  }
  const geo = new BufferGeometry();
  geo.setIndex(indices);
  geo.setAttribute('position', new BufferAttribute(new Float32Array(positions), 3));
  geo.setAttribute('normal', new BufferAttribute(new Float32Array(normals), 3));
  geo.computeVertexNormals();
  return geo;
}

function hillShape(width, base, amp, phase) {
  const s = new Shape();
  const half = width / 2;
  s.moveTo(-half, -base - 3);
  s.lineTo(-half, -base);
  const steps = 48;
  for (let i = 0; i <= steps; i += 1) {
    const x = -half + (width * i) / steps;
    const y = -base + Math.sin(i / steps * Math.PI * 2 + phase) * amp + Math.sin(i / steps * Math.PI * 5 + phase) * amp * 0.25;
    s.lineTo(x, y);
  }
  s.lineTo(half, -base - 3);
  s.lineTo(-half, -base - 3);
  return s;
}

function createStars(count, seed = 7) {
  let x = seed;
  const rand = () => {
    x = (x * 16807) % 2147483647;
    return (x - 1) / 2147483646;
  };
  const arr = new Float32Array(count * 3);
  for (let i = 0; i < count; i += 1) {
    arr[i * 3] = (rand() - 0.5) * 7;
    arr[i * 3 + 1] = rand() * 4.4 - 0.6;
    arr[i * 3 + 2] = -3.4 - rand() * 0.6;
  }
  const geo = new BufferGeometry();
  geo.setAttribute('position', new BufferAttribute(arr, 3));
  return geo;
}

export function createHeroScene(canvas, { tier = 'high', reducedMotion = false, onFirstFrame } = {}) {
  const isHigh = tier === 'high';

  const renderer = new WebGLRenderer({
    canvas,
    antialias: true,
    alpha: true,
    powerPreference: isHigh ? 'high-performance' : 'low-power'
  });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, isHigh ? 2 : 1.5));
  renderer.outputColorSpace = SRGBColorSpace;
  renderer.toneMapping = ACESFilmicToneMapping;
  renderer.toneMappingExposure = 0.95;

  const scene = new Scene();
  const pmrem = new PMREMGenerator(renderer);
  const envRT = pmrem.fromScene(new RoomEnvironment(), 0.04);
  scene.environment = envRT.texture;

  const camera = new PerspectiveCamera(32, 1, 0.1, 50);
  camera.position.set(0, 0.15, 7.8);

  // نور: یک نور کلیدی گرم از بالا، یک نور لبهٔ سرد از پشت، و نور محیطی آسمان/زمین
  scene.add(new HemisphereLight(COLORS.skyLight, COLORS.groundLight, 0.55));
  const key = new DirectionalLight('#fff3df', 2.1);
  key.position.set(-3, 4, 5);
  scene.add(key);
  const rim = new DirectionalLight('#9fc7ff', 1.1);
  rim.position.set(3, 2, -4);
  scene.add(rim);

  // ---- سطح عقب: آسمان و ستاره‌ها (قاب طاقی‌شکل در CSS است)
  const back = new Group();
  const stars = new Points(
    createStars(isHigh ? 80 : 45),
    new PointsMaterial({ color: COLORS.star, size: 0.04, sizeAttenuation: true, transparent: true, opacity: 0.8, depthWrite: false })
  );
  back.add(stars);
  scene.add(back);

  // ---- کانون: گهوارهٔ ماه و مروارید
  const cradle = new Group();
  const crescent = new Mesh(
    createCrescentGeometry({
      radius: 1.25,
      thickness: 0.34,
      arcStart: Math.PI * 1.08,
      arcEnd: Math.PI * 1.92,
      tubular: isHigh ? 120 : 64,
      radial: isHigh ? 40 : 24
    }),
    new MeshStandardMaterial({ color: COLORS.moon, roughness: 0.32, metalness: 0.35, envMapIntensity: 0.6 })
  );
  const pearl = new Mesh(
    new SphereGeometry(0.42, isHigh ? 64 : 40, isHigh ? 48 : 28),
    new MeshPhysicalMaterial({
      color: COLORS.pearl,
      roughness: 0.16,
      metalness: 0,
      clearcoat: 1,
      clearcoatRoughness: 0.12,
      iridescence: 0.55,
      iridescenceIOR: 1.3,
      sheen: 0.6,
      sheenColor: new Color(COLORS.pearlSheen),
      sheenRoughness: 0.4,
      envMapIntensity: 0.85
    })
  );
  pearl.position.set(0, -1.25 + 0.34 + 0.42 - 0.1, 0.04);
  cradle.add(crescent, pearl);
  cradle.position.set(0, 0.55, 0);
  cradle.rotation.x = -0.32;
  scene.add(cradle);

  // ---- سطح جلو: تپه‌ها
  const front = new Group();
  const hillFar = new Mesh(new ShapeGeometry(hillShape(14, 1.75, 0.3, 0.9)), new MeshBasicMaterial({ color: COLORS.hillFar, toneMapped: false }));
  hillFar.position.z = 0.6;
  const hillNear = new Mesh(new ShapeGeometry(hillShape(14, 2.15, 0.22, 2.6)), new MeshBasicMaterial({ color: COLORS.hillNear, toneMapped: false }));
  hillNear.position.z = 1.4;
  front.add(hillFar, hillNear);
  scene.add(front);

  // ---- وضعیت حرکت
  const state = {
    pointer: { x: 0, y: 0 },
    eased: { x: 0, y: 0 },
    scroll: 0,
    easedScroll: 0,
    introStart: reducedMotion ? -1 : performance.now(),
    active: true,
    frame: 0,
    rendered: false,
    width: 1,
    height: 1
  };
  const INTRO_MS = 2400;

  function frameCamera() {
    const aspect = state.width / state.height;
    camera.aspect = aspect;
    // در قاب‌های باریک دوربین عقب‌تر می‌رود تا کانون کامل دیده شود
    camera.position.z = aspect < 1 ? 7.8 / Math.max(aspect, 0.62) : 7.8;
    camera.updateProjectionMatrix();
  }

  function resize(width, height) {
    state.width = Math.max(1, width);
    state.height = Math.max(1, height);
    renderer.setSize(state.width, state.height, false);
    frameCamera();
    requestRender();
  }

  function update(now) {
    let moving = false;

    // تکان آرام اولیهٔ گهواره، یک بار
    if (state.introStart >= 0) {
      const t = (now - state.introStart) / INTRO_MS;
      if (t < 1) {
        cradle.rotation.z = 0.14 * Math.exp(-3.2 * t) * Math.cos(t * Math.PI * 3);
        moving = true;
      } else {
        cradle.rotation.z = 0;
        state.introStart = -1;
      }
    }

    if (!reducedMotion) {
      const k = 0.08;
      state.eased.x += (state.pointer.x - state.eased.x) * k;
      state.eased.y += (state.pointer.y - state.eased.y) * k;
      state.easedScroll += (state.scroll - state.easedScroll) * 0.12;
      if (
        Math.abs(state.pointer.x - state.eased.x) > 0.0005 ||
        Math.abs(state.pointer.y - state.eased.y) > 0.0005 ||
        Math.abs(state.scroll - state.easedScroll) > 0.0005
      ) {
        moving = true;
      }
    }

    // حرکت محدود: دوربین حداکثر ۰٫۲۵ واحد، گهواره حداکثر ۷ درجه
    camera.position.x = state.eased.x * 0.25;
    camera.position.y = 0.15 + state.eased.y * 0.15;
    camera.lookAt(0, 0.1, 0);
    cradle.rotation.y = state.eased.x * 0.12;
    // پارالاکس سه سطح هنگام اسکرول
    back.position.y = state.easedScroll * 0.25;
    cradle.position.y = 0.55 - state.easedScroll * 0.35;
    front.position.y = -state.easedScroll * 0.6;

    return moving;
  }

  function tick(now) {
    state.frame = 0;
    if (!state.active) return;
    const moving = update(now);
    renderer.render(scene, camera);
    if (!state.rendered) {
      state.rendered = true;
      if (onFirstFrame) onFirstFrame();
    }
    if (moving) state.frame = requestAnimationFrame(tick);
  }

  function requestRender() {
    if (!state.active || state.frame) return;
    state.frame = requestAnimationFrame(tick);
  }

  return {
    resize,
    /** x و y بین ‎-1 و 1 */
    setPointer(x, y) {
      if (reducedMotion) return;
      state.pointer.x = Math.max(-1, Math.min(1, x));
      state.pointer.y = Math.max(-1, Math.min(1, y));
      requestRender();
    },
    /** 0 در بالای Hero تا 1 وقتی Hero از دید خارج شده */
    setScroll(p) {
      if (reducedMotion) return;
      state.scroll = Math.max(0, Math.min(1, p));
      requestRender();
    },
    setActive(active) {
      state.active = active;
      if (!active && state.frame) {
        cancelAnimationFrame(state.frame);
        state.frame = 0;
      }
      if (active) requestRender();
    },
    dispose() {
      state.active = false;
      if (state.frame) cancelAnimationFrame(state.frame);
      scene.traverse((obj) => {
        if (obj.geometry) obj.geometry.dispose();
        if (obj.material) obj.material.dispose();
      });
      envRT.dispose();
      pmrem.dispose();
      renderer.dispose();
    }
  };
}
