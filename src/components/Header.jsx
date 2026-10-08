import { useEffect, useRef, useState } from 'react';
import { brand, nav } from '../data/content.js';
import { useActiveSection } from '../hooks/useActiveSection.js';
import Icon from './Icon.jsx';
import Logo from './Logo.jsx';
import './Header.css';

const IDS = nav.map((n) => n.id);

export default function Header() {
  const active = useActiveSection(IDS);
  const [open, setOpen] = useState(false);
  const toggleRef = useRef(null);
  const panelRef = useRef(null);

  // بستن منوی موبایل با Escape و بازگرداندن فوکوس
  useEffect(() => {
    if (!open) return undefined;
    const first = panelRef.current?.querySelector('a, button');
    first?.focus();
    const onKey = (e) => {
      if (e.key === 'Escape') {
        setOpen(false);
        toggleRef.current?.focus();
      }
      if (e.key === 'Tab' && panelRef.current) {
        const items = panelRef.current.querySelectorAll('a, button');
        const list = [toggleRef.current, ...items];
        const idx = list.indexOf(document.activeElement);
        if (e.shiftKey && idx <= 0) {
          e.preventDefault();
          list[list.length - 1].focus();
        } else if (!e.shiftKey && idx === list.length - 1) {
          e.preventDefault();
          list[0].focus();
        }
      }
    };
    document.addEventListener('keydown', onKey);
    return () => document.removeEventListener('keydown', onKey);
  }, [open]);

  // با بزرگ شدن صفحه، منوی موبایل بسته شود
  useEffect(() => {
    const mq = window.matchMedia('(min-width: 1024px)');
    const onChange = () => mq.matches && setOpen(false);
    mq.addEventListener('change', onChange);
    return () => mq.removeEventListener('change', onChange);
  }, []);

  const link = (item, extra = '') => (
    <a
      key={item.id}
      href={`#${item.id}`}
      className={`nav-link${extra}`}
      aria-current={active === item.id ? 'location' : undefined}
      onClick={() => setOpen(false)}
    >
      {item.label}
    </a>
  );

  return (
    <header className="site-header on-night">
      <div className="container site-header__inner">
        <a href="#top" className="site-header__brand" aria-label={`${brand.name}، بازگشت به بالای صفحه`}>
          <Logo />
        </a>

        <nav className="site-nav" aria-label="منوی اصلی">
          {nav.map((item) => link(item))}
        </nav>

        <a href="#start" className="btn btn--accent btn--sm site-header__cta">
          {brand.primaryCta}
        </a>

        <button
          ref={toggleRef}
          type="button"
          className="icon-btn site-header__toggle"
          aria-expanded={open}
          aria-controls="mobile-menu"
          aria-label={open ? 'بستن منو' : 'باز کردن منو'}
          onClick={() => setOpen((v) => !v)}
        >
          <Icon name={open ? 'X' : 'Menu'} size={22} />
        </button>
      </div>

      <div id="mobile-menu" ref={panelRef} className="mobile-menu" hidden={!open}>
        <nav aria-label="منوی موبایل" className="mobile-menu__nav">
          {nav.map((item) => link(item, ' nav-link--block'))}
        </nav>
        <a href="#start" className="btn btn--accent mobile-menu__cta" onClick={() => setOpen(false)}>
          {brand.primaryCta}
        </a>
      </div>
    </header>
  );
}
