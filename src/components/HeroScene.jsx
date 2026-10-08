import { useEffect, useRef, useState } from 'react';
import { getDeviceTier } from '../hooks/deviceTier.js';
import { useReducedMotion } from '../hooks/useReducedMotion.js';
import './HeroScene.css';

/**
 * قاب صحنهٔ سه‌بعدی.
 * پوستر ثابت همیشه اول نمایش داده می‌شود (و جایگزین کامل در دستگاه ضعیف یا بدون WebGL است).
 * three.js فقط بعد از رندر اولیهٔ صفحه و فقط در دستگاه‌های توانمند بارگذاری می‌شود.
 */
export default function HeroScene({ alt, pointerTargetRef }) {
  const wrapRef = useRef(null);
  const canvasRef = useRef(null);
  const sceneRef = useRef(null);
  const [ready, setReady] = useState(false);
  const reducedMotion = useReducedMotion();

  useEffect(() => {
    const tier = getDeviceTier();
    if (tier === 'none' || tier === 'low') return undefined;

    let cancelled = false;
    let cleanup = () => {};

    const start = async () => {
      const { createHeroScene } = await import('../scene/heroScene.js');
      if (cancelled || !canvasRef.current || !wrapRef.current) return;

      const api = createHeroScene(canvasRef.current, {
        tier,
        reducedMotion,
        onFirstFrame: () => !cancelled && setReady(true)
      });
      sceneRef.current = api;

      const wrap = wrapRef.current;
      const ro = new ResizeObserver(([entry]) => {
        const { width, height } = entry.contentRect;
        api.resize(width, height);
      });
      ro.observe(wrap);

      // فقط وقتی صحنه در دید است و تب فعال است رندر کن
      let inView = true;
      const sync = () => api.setActive(inView && document.visibilityState === 'visible');
      const io = new IntersectionObserver(([e]) => {
        inView = e.isIntersecting;
        sync();
      });
      io.observe(wrap);
      document.addEventListener('visibilitychange', sync);

      // نشانگر روی کل بخش Hero؛ لمس هم همین رویداد را می‌فرستد
      const target = pointerTargetRef?.current || wrap;
      const onPointer = (ev) => {
        const r = target.getBoundingClientRect();
        api.setPointer(((ev.clientX - r.left) / r.width) * 2 - 1, -(((ev.clientY - r.top) / r.height) * 2 - 1));
      };
      const onLeave = () => api.setPointer(0, 0);
      target.addEventListener('pointermove', onPointer, { passive: true });
      target.addEventListener('pointerleave', onLeave);

      const onScroll = () => {
        const r = target.getBoundingClientRect();
        api.setScroll(-r.top / Math.max(1, r.height));
      };
      window.addEventListener('scroll', onScroll, { passive: true });

      cleanup = () => {
        ro.disconnect();
        io.disconnect();
        document.removeEventListener('visibilitychange', sync);
        target.removeEventListener('pointermove', onPointer);
        target.removeEventListener('pointerleave', onLeave);
        window.removeEventListener('scroll', onScroll);
        api.dispose();
        sceneRef.current = null;
      };
    };

    // بعد از رندر اولیه و نمایش تیتر، صحنه را بارگذاری کن
    const idle = window.requestIdleCallback || ((cb) => setTimeout(cb, 200));
    const cancelIdle = window.cancelIdleCallback || clearTimeout;
    const handle = idle(() => {
      start().catch(() => {
        /* اگر بارگذاری صحنه شکست خورد، پوستر باقی می‌ماند */
      });
    });

    return () => {
      cancelled = true;
      cancelIdle(handle);
      cleanup();
    };
  }, [reducedMotion, pointerTargetRef]);

  return (
    <figure className="hero-scene" ref={wrapRef}>
      <img
        className="hero-scene__poster"
        src="./hero-poster.webp"
        alt=""
        width="900"
        height="1000"
        fetchpriority="high"
        decoding="async"
      />
      <canvas ref={canvasRef} className={`hero-scene__canvas${ready ? ' is-ready' : ''}`} aria-hidden="true" />
      <figcaption className="sr-only">{alt}</figcaption>
    </figure>
  );
}
