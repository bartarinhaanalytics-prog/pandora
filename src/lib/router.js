import { useEffect, useState } from 'react';

/*
 * مسیریابی ساده با هش (#/health/women). روی هر میزبان ایستا بدون تنظیم سرور کار می‌کند.
 * هش‌هایی که با «#/» شروع نمی‌شوند لنگرهای داخل صفحه‌اند و به مسیر دست نمی‌زنند.
 */
function parse() {
  const raw = window.location.hash;
  if (!raw.startsWith('#/')) return { path: '/', parts: [], query: new URLSearchParams(), anchor: raw.slice(1) };
  const [p, q = ''] = raw.slice(1).split('?');
  const path = p.replace(/\/+$/, '') || '/';
  return { path, parts: path.split('/').filter(Boolean).map(decodeURIComponent), query: new URLSearchParams(q), anchor: '' };
}

export function useRoute() {
  const [route, setRoute] = useState(parse);
  useEffect(() => {
    const on = () => {
      const next = parse();
      setRoute((prev) => {
        // لنگر داخل همین صفحه (مثل پرش به محتوای اصلی): مسیر عوض نمی‌شود
        if (!window.location.hash.startsWith('#/') && window.location.hash) {
          return prev.path === '/' ? { ...prev, anchor: next.anchor } : prev;
        }
        if (next.path !== prev.path || next.query.toString() !== prev.query.toString()) {
          window.scrollTo(0, 0);
        }
        return next;
      });
    };
    window.addEventListener('hashchange', on);
    return () => window.removeEventListener('hashchange', on);
  }, []);
  return route;
}

export const href = (path, query) => {
  const q = query ? `?${new URLSearchParams(query)}` : '';
  return `#${path}${q}`;
};

export function navigate(path, query) {
  window.location.hash = href(path, query).slice(1);
}
