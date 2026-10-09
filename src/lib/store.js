import { useCallback, useEffect, useState } from 'react';

/*
 * دادهٔ کاربر تا راه‌اندازی سرور روی همین دستگاه می‌ماند (localStorage).
 * اگر مرورگر اجازه ندهد، فقط برای همین بازدید در حافظه نگه داشته می‌شود.
 */
const PREFIX = 'dordooneh:';
const memory = new Map();

export function read(key, fallback) {
  try {
    const v = localStorage.getItem(PREFIX + key);
    return v === null ? fallback : JSON.parse(v);
  } catch {
    return memory.has(key) ? memory.get(key) : fallback;
  }
}

export function write(key, value) {
  memory.set(key, value);
  try {
    localStorage.setItem(PREFIX + key, JSON.stringify(value));
  } catch {
    /* حافظهٔ مرورگر در دسترس نیست */
  }
  window.dispatchEvent(new CustomEvent('dordooneh:store', { detail: key }));
}

export function useStored(key, fallback) {
  const [value, setValue] = useState(() => read(key, fallback));
  useEffect(() => {
    const on = (e) => {
      if (!e.detail || e.detail === key) setValue(read(key, fallback));
    };
    window.addEventListener('dordooneh:store', on);
    return () => window.removeEventListener('dordooneh:store', on);
    // fallback عمداً در وابستگی‌ها نیست تا آرایه/شیء تازه حلقه نسازد
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [key]);
  const set = useCallback(
    (next) => {
      const v = typeof next === 'function' ? next(read(key, fallback)) : next;
      write(key, v);
    },
    // eslint-disable-next-line react-hooks/exhaustive-deps
    [key]
  );
  return [value, set];
}

export const uid = () => Math.random().toString(36).slice(2, 10) + Date.now().toString(36);
