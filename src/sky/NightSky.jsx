import { useEffect, useRef } from 'react';
import { useMotion } from '../hooks/useMotion.jsx';
import { fragment, vertex } from './skyShader.js';
import './NightSky.css';

/*
 * آسمان زندهٔ پشت کل صفحه. با پیمایش، دوربین آرام بالا می‌رود و ستاره‌ها
 * در سه عمق جابه‌جا می‌شوند. وقتی حرکت خاموش است فقط یک فریم ثابت رسم می‌شود.
 * بدون WebGL، تصویر ثابت (sky-*.webp) زیر بوم دیده می‌شود.
 */
export default function NightSky() {
  const ref = useRef(null);
  const { playing } = useMotion();
  const api = useRef(null);

  useEffect(() => {
    const canvas = ref.current;
    const gl = canvas.getContext('webgl', { antialias: false, alpha: false, powerPreference: 'low-power' });
    if (!gl) return undefined;
    const compile = (type, src) => {
      const s = gl.createShader(type);
      gl.shaderSource(s, src);
      gl.compileShader(s);
      return gl.getShaderParameter(s, gl.COMPILE_STATUS) ? s : null;
    };
    const vs = compile(gl.VERTEX_SHADER, vertex);
    const fs = compile(gl.FRAGMENT_SHADER, fragment);
    if (!vs || !fs) return undefined;
    const prog = gl.createProgram();
    gl.attachShader(prog, vs);
    gl.attachShader(prog, fs);
    gl.linkProgram(prog);
    if (!gl.getProgramParameter(prog, gl.LINK_STATUS)) return undefined;
    gl.useProgram(prog);
    gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 3, -1, -1, 3]), gl.STATIC_DRAW);
    const loc = gl.getAttribLocation(prog, 'p');
    gl.enableVertexAttribArray(loc);
    gl.vertexAttribPointer(loc, 2, gl.FLOAT, false, 0, 0);
    const uRes = gl.getUniformLocation(prog, 'uRes');
    const uTime = gl.getUniformLocation(prog, 'uTime');
    const uScroll = gl.getUniformLocation(prog, 'uScroll');

    // گوشی‌ها با وضوح کمتر رسم می‌کنند؛ آسمان نرم است و ستاره‌ها هنوز تیزند
    const coarse = matchMedia('(pointer: coarse)').matches;
    const maxDpr = coarse ? 1 : 1.5;
    let w = 0;
    let h = 0;
    const resize = () => {
      const dpr = Math.min(window.devicePixelRatio || 1, maxDpr);
      w = Math.round(canvas.clientWidth * dpr);
      h = Math.round(canvas.clientHeight * dpr);
      canvas.width = w;
      canvas.height = h;
      gl.viewport(0, 0, w, h);
    };

    let scroll = 0;
    let shown = 0;
    let time = 3;
    let last = performance.now();
    let raf = 0;
    let live = false;
    const readScroll = () => {
      const max = document.documentElement.scrollHeight - window.innerHeight;
      scroll = max > 0 ? window.scrollY / max : 0;
    };
    const draw = () => {
      gl.uniform2f(uRes, w, h);
      gl.uniform1f(uTime, time);
      gl.uniform1f(uScroll, shown);
      gl.drawArrays(gl.TRIANGLES, 0, 3);
      canvas.dataset.ready = '1';
    };
    const tick = (now) => {
      const dt = Math.min(0.05, (now - last) / 1000);
      last = now;
      time += dt;
      shown += (scroll - shown) * Math.min(1, dt * 4);
      draw();
      raf = requestAnimationFrame(tick);
    };
    const onScroll = () => {
      readScroll();
      if (!live) {
        shown = scroll;
      }
    };
    const onResize = () => {
      resize();
      draw();
    };

    resize();
    readScroll();
    shown = scroll;
    draw();
    window.addEventListener('resize', onResize);
    window.addEventListener('scroll', onScroll, { passive: true });

    const onVis = () => {
      if (document.hidden) cancelAnimationFrame(raf);
      else if (live) {
        last = performance.now();
        raf = requestAnimationFrame(tick);
      }
    };
    document.addEventListener('visibilitychange', onVis);

    api.current = {
      play() {
        if (live) return;
        live = true;
        last = performance.now();
        raf = requestAnimationFrame(tick);
      },
      stop() {
        live = false;
        cancelAnimationFrame(raf);
        draw();
      }
    };

    return () => {
      cancelAnimationFrame(raf);
      window.removeEventListener('resize', onResize);
      window.removeEventListener('scroll', onScroll);
      document.removeEventListener('visibilitychange', onVis);
      api.current = null;
    };
  }, []);

  useEffect(() => {
    if (!api.current) return;
    if (playing) api.current.play();
    else api.current.stop();
  }, [playing]);

  return (
    <div className="sky" aria-hidden="true">
      <canvas ref={ref} className="sky__canvas" />
    </div>
  );
}
