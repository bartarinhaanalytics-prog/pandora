import { useEffect, useRef, useState } from 'react';
import { gallery } from '../data/content.js';
import { useReducedMotion } from '../hooks/useReducedMotion.js';
import Cover from './Cover.jsx';
import Icon from './Icon.jsx';
import './Gallery.css';

export default function Gallery() {
  const [index, setIndex] = useState(null);
  const dialogRef = useRef(null);
  const openerRef = useRef(null);
  const listRef = useRef(null);
  const reduced = useReducedMotion();

  // پارالاکس کنترل‌شده: لایه‌های هر جلد حداکثر ۱۰ پیکسل جابه‌جا می‌شوند
  useEffect(() => {
    const list = listRef.current;
    if (!list || reduced) return undefined;
    let raf = 0;
    const update = () => {
      raf = 0;
      const vh = window.innerHeight;
      list.querySelectorAll('.gcard').forEach((card) => {
        const r = card.getBoundingClientRect();
        if (r.bottom < 0 || r.top > vh) return;
        const p = (r.top + r.height / 2 - vh / 2) / vh; // ‎-1..1
        card.querySelectorAll('[data-depth]').forEach((g) => {
          const d = Number(g.getAttribute('data-depth'));
          g.style.transform = `translateY(${(p * d * 10).toFixed(2)}px)`;
        });
      });
    };
    const onScroll = () => {
      if (!raf) raf = requestAnimationFrame(update);
    };
    update();
    window.addEventListener('scroll', onScroll, { passive: true });
    return () => {
      window.removeEventListener('scroll', onScroll);
      if (raf) cancelAnimationFrame(raf);
    };
  }, [reduced]);

  useEffect(() => {
    const d = dialogRef.current;
    if (!d) return;
    if (index !== null && !d.open) d.showModal();
    if (index === null && d.open) d.close();
  }, [index]);

  const open = (i, el) => {
    openerRef.current = el;
    setIndex(i);
  };
  const close = () => {
    setIndex(null);
    openerRef.current?.focus();
  };
  const go = (delta) => setIndex((i) => (i + delta + gallery.length) % gallery.length);

  const item = index !== null ? gallery[index] : null;

  return (
    <section id="gallery" className="section gallery on-night" aria-labelledby="gallery-title">
      <div className="container">
        <div className="section-head">
          <p className="eyebrow">قصه و لالایی</p>
          <h2 id="gallery-title" className="h2">
            کتابخانهٔ شب‌های شما
          </h2>
          <p className="lead">چند نمونه از قصه‌هایی که قصه‌ساز می‌نویسد و لالایی‌هایی که در دردونه هست.</p>
        </div>
      </div>

      <ul ref={listRef} className="gallery__list" role="list">
        {gallery.map((g, i) => (
          <li key={g.id} className={`gcard gcard--${i + 1}`}>
            <button
              type="button"
              className="gcard__btn"
              onClick={(e) => open(i, e.currentTarget)}
              aria-haspopup="dialog"
            >
              <Cover art={g.art} className="gcard__art" />
              <span className="gcard__meta">
                <span className="gcard__kind">{g.kind}</span>
                <span className="gcard__title">{g.title}</span>
                <span className="gcard__sub">{g.meta}</span>
              </span>
              <span className="sr-only">، دیدن جزئیات</span>
            </button>
          </li>
        ))}
      </ul>

      <dialog
        ref={dialogRef}
        className="gdialog"
        aria-labelledby="gdialog-title"
        onCancel={(e) => {
          e.preventDefault();
          close();
        }}
        onClick={(e) => {
          if (e.target === dialogRef.current) close();
        }}
      >
        {item && (
          <div className="gdialog__inner on-night">
            <button type="button" className="icon-btn gdialog__close" onClick={close} aria-label="بستن">
              <Icon name="X" size={22} />
            </button>
            <Cover art={item.art} className="gdialog__art" />
            <div className="gdialog__body">
              <p className="gcard__kind">{item.kind}</p>
              <h3 id="gdialog-title" className="gdialog__title">
                {item.title}
              </h3>
              <p className="gdialog__sub">{item.meta}</p>
              <p className="gdialog__text">{item.text}</p>
              <div className="gdialog__nav">
                <button type="button" className="btn btn--ghost btn--sm" onClick={() => go(-1)} aria-label="مورد قبلی">
                  <Icon name="ChevronRight" size={18} />
                  قبلی
                </button>
                <span className="gdialog__count num" aria-live="polite">
                  {(index + 1).toLocaleString('fa-IR')} از {gallery.length.toLocaleString('fa-IR')}
                </span>
                <button type="button" className="btn btn--ghost btn--sm" onClick={() => go(1)} aria-label="مورد بعدی">
                  بعدی
                  <Icon name="ChevronLeft" size={18} />
                </button>
              </div>
            </div>
          </div>
        )}
      </dialog>
    </section>
  );
}
