import { useEffect, useRef } from 'react';
import { steps } from '../data/content.js';
import { useReducedMotion } from '../hooks/useReducedMotion.js';
import './HowItWorks.css';

const FA = ['۱', '۲', '۳', '۴'];

export default function HowItWorks() {
  const listRef = useRef(null);
  const reduced = useReducedMotion();

  // پر شدن مسیر هم‌پای اسکرول؛ محتوا از اول کامل دیده می‌شود
  useEffect(() => {
    const el = listRef.current;
    if (!el) return undefined;
    if (reduced) {
      el.style.setProperty('--p', '1');
      return undefined;
    }
    let raf = 0;
    const update = () => {
      raf = 0;
      const r = el.getBoundingClientRect();
      const vh = window.innerHeight;
      const p = Math.min(1, Math.max(0, (vh * 0.8 - r.top) / (r.height + vh * 0.3)));
      el.style.setProperty('--p', p.toFixed(3));
    };
    const onScroll = () => {
      if (!raf) raf = requestAnimationFrame(update);
    };
    update();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    return () => {
      window.removeEventListener('scroll', onScroll);
      window.removeEventListener('resize', onScroll);
      if (raf) cancelAnimationFrame(raf);
    };
  }, [reduced]);

  return (
    <section id="how" className="section how" aria-labelledby="how-title">
      <div className="container">
        <div className="section-head">
          <p className="eyebrow">نحوهٔ کار</p>
          <h2 id="how-title" className="h2">
            از اولین سؤال تا قصهٔ شب، در چهار قدم
          </h2>
        </div>
        <ol ref={listRef} className="steps" role="list">
          {steps.map((s, i) => (
            <li key={s.title} className="step">
              <span className="step__num" aria-hidden="true">
                {FA[i]}
              </span>
              <h3 className="step__title">
                <span className="sr-only">قدم {FA[i]}: </span>
                {s.title}
              </h3>
              <p className="step__text">{s.text}</p>
            </li>
          ))}
        </ol>
      </div>
    </section>
  );
}
