import { createContext, useCallback, useContext, useEffect, useMemo, useState } from 'react';
import { useReducedMotion } from './useReducedMotion.js';

/*
 * یک کلید برای همهٔ ویدیوهای پس‌زمینه (WCAG 2.2.2).
 * با «کاهش حرکت» یا «صرفه‌جویی داده» پیش‌فرض خاموش است و فقط تصویر ثابت می‌ماند.
 */
const MotionContext = createContext({ playing: false, toggle: () => {} });
const KEY = 'dordooneh:motion';

function readPref() {
  try {
    return localStorage.getItem(KEY);
  } catch {
    return null;
  }
}

export function MotionProvider({ children }) {
  const reduced = useReducedMotion();
  const saveData = typeof navigator !== 'undefined' && navigator.connection?.saveData;
  const [pref, setPref] = useState(readPref);

  const playing = pref ? pref === 'on' : !reduced && !saveData;

  const toggle = useCallback(() => {
    const next = playing ? 'off' : 'on';
    setPref(next);
    try {
      localStorage.setItem(KEY, next);
    } catch {
      /* حافظهٔ مرورگر در دسترس نیست؛ فقط برای همین بازدید */
    }
  }, [playing]);

  useEffect(() => {
    document.documentElement.dataset.motion = playing ? 'on' : 'off';
  }, [playing]);

  const value = useMemo(() => ({ playing, toggle }), [playing, toggle]);
  return <MotionContext.Provider value={value}>{children}</MotionContext.Provider>;
}

export const useMotion = () => useContext(MotionContext);
