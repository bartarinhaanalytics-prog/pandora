import { useEffect, useRef, useState } from 'react';
import { nav } from '../data/content.js';
import { useMotion } from '../hooks/useMotion.jsx';
import Icon from './Icon.jsx';
import Logo from './Logo.jsx';
import './Header.css';

export function MotionToggle({ className = '' }) {
  const { playing, toggle } = useMotion();
  return (
    <button type="button" className={`motion-toggle ${className}`} onClick={toggle} aria-pressed={!playing}>
      <Icon name={playing ? 'Pause' : 'Play'} size={18} />
      <span>{playing ? 'توقف تصاویر متحرک' : 'پخش تصاویر متحرک'}</span>
    </button>
  );
}

export default function Header() {
  const [open, setOpen] = useState(false);
  const [solid, setSolid] = useState(false);
  const sheet = useRef(null);
  const opener = useRef(null);

  useEffect(() => {
    const on = () => setSolid(window.scrollY > 40);
    on();
    window.addEventListener('scroll', on, { passive: true });
    return () => window.removeEventListener('scroll', on);
  }, []);

  useEffect(() => {
    if (!open) return undefined;
    const el = sheet.current;
    const focusables = () => [...el.querySelectorAll('a, button')];
    focusables()[0]?.focus();
    const onKey = (e) => {
      if (e.key === 'Escape') {
        setOpen(false);
        opener.current?.focus();
      }
      if (e.key === 'Tab') {
        const f = focusables();
        const first = f[0];
        const last = f[f.length - 1];
        if (e.shiftKey && document.activeElement === first) {
          e.preventDefault();
          last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
          e.preventDefault();
          first.focus();
        }
      }
    };
    document.addEventListener('keydown', onKey);
    document.body.style.overflow = 'hidden';
    return () => {
      document.removeEventListener('keydown', onKey);
      document.body.style.overflow = '';
    };
  }, [open]);

  const close = () => setOpen(false);

  return (
    <header className={`header ${solid ? 'header--solid' : ''}`}>
      <div className="wrap header__bar">
        <a href="#top" className="header__home" aria-label="دردونه، بازگشت به بالای صفحه">
          <Logo />
        </a>
        <nav className="header__nav" aria-label="بخش‌های صفحه">
          {nav.map((n) => (
            <a key={n.id} href={`#${n.id}`}>
              {n.label}
            </a>
          ))}
        </nav>
        <div className="header__end">
          <MotionToggle className="motion-toggle--compact" />
          <a href="#start" className="header__cta">
            ثبت‌نام رایگان
          </a>
          <button
            ref={opener}
            type="button"
            className="header__menu"
            aria-expanded={open}
            aria-controls="mobile-menu"
            onClick={() => setOpen(true)}
          >
            <Icon name="Menu" size={22} />
            <span className="sr-only">باز کردن فهرست</span>
          </button>
        </div>
      </div>

      {open && (
        <div className="sheet" id="mobile-menu" role="dialog" aria-modal="true" aria-label="فهرست" ref={sheet}>
          <div className="sheet__top">
            <Logo />
            <button
              type="button"
              className="header__menu"
              onClick={() => {
                close();
                opener.current?.focus();
              }}
            >
              <Icon name="X" size={22} />
              <span className="sr-only">بستن فهرست</span>
            </button>
          </div>
          <nav aria-label="بخش‌های صفحه" className="sheet__nav">
            {nav.map((n) => (
              <a key={n.id} href={`#${n.id}`} onClick={close}>
                {n.label}
              </a>
            ))}
          </nav>
          <MotionToggle />
          <a href="#start" className="btn btn--line" onClick={close}>
            ثبت‌نام رایگان
          </a>
        </div>
      )}
    </header>
  );
}
