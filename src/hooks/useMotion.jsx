import { createContext, useContext, useEffect, useMemo } from 'react';
import { useReducedMotion } from './useReducedMotion.js';

/*
 * حرکت آسمان پس‌زمینه. با «کاهش حرکت» سیستم‌عامل یا «صرفه‌جویی داده»
 * آسمان ثابت می‌ماند و فقط یک فریم رسم می‌شود.
 */
const MotionContext = createContext({ playing: false });

export function MotionProvider({ children }) {
  const reduced = useReducedMotion();
  const saveData = typeof navigator !== 'undefined' && Boolean(navigator.connection?.saveData);
  const playing = !reduced && !saveData;

  useEffect(() => {
    document.documentElement.dataset.motion = playing ? 'on' : 'off';
  }, [playing]);

  const value = useMemo(() => ({ playing }), [playing]);
  return <MotionContext.Provider value={value}>{children}</MotionContext.Provider>;
}

export const useMotion = () => useContext(MotionContext);
