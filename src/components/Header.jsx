import { useEffect, useRef, useState } from 'react';
import { href } from '../lib/router.js';
import { primaryNav, sitemap } from '../data/site.js';
import Icon from './Icon.jsx';
import Logo from './Logo.jsx';
import './Header.css';

const isActive = (current, path) => current === path || (path !== '/' && current.startsWith(`${path}/`));

export default function Header({ path }) {
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

  useEffect(() => setOpen(false), [path]);

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

  return (
    <header className={`header ${solid || path !== '/' ? 'header--solid' : ''}`}>
      <div className="wrap header__bar">
        <a href={href('/')} className="header__home" aria-label="دردونه، صفحهٔ اول">
          <Logo />
        </a>
        <nav className="header__nav" aria-label="بخش‌های اصلی">
          {primaryNav.map((n) => (
            <a key={n.path} href={href(n.path)} aria-current={isActive(path, n.path) ? 'page' : undefined}>
              {n.label}
            </a>
          ))}
        </nav>
        <div className="header__end">
          <a href={href('/search')} className="header__icon" aria-current={path === '/search' ? 'page' : undefined}>
            <Icon name="Search" size={20} />
            <span className="sr-only">جستجو</span>
          </a>
          <a href={href('/me')} className="header__icon header__icon--me" aria-current={isActive(path, '/me') ? 'page' : undefined}>
            <Icon name="User" size={20} />
            <span className="sr-only">پروفایل و خانواده</span>
          </a>
          <button
            ref={opener}
            type="button"
            className="header__menu"
            aria-expanded={open}
            aria-controls="site-menu"
            onClick={() => setOpen(true)}
          >
            <Icon name="Menu" size={22} />
            <span className="sr-only">همهٔ بخش‌ها</span>
          </button>
        </div>
      </div>

      {open && (
        <div className="sheet" id="site-menu" role="dialog" aria-modal="true" aria-label="همهٔ بخش‌ها" ref={sheet}>
          <div className="sheet__top">
            <Logo />
            <button
              type="button"
              className="header__menu header__menu--show"
              onClick={() => {
                setOpen(false);
                opener.current?.focus();
              }}
            >
              <Icon name="X" size={22} />
              <span className="sr-only">بستن</span>
            </button>
          </div>
          <div className="sheet__groups">
            {sitemap.map((g) => (
              <nav key={g.title} aria-label={g.title} className="sheet__group">
                <h2>{g.title}</h2>
                {g.links.map((l) => (
                  <a key={l.path} href={href(l.path)} aria-current={path === l.path ? 'page' : undefined}>
                    {l.label}
                  </a>
                ))}
              </nav>
            ))}
          </div>
        </div>
      )}
    </header>
  );
}
