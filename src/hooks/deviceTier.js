/**
 * Rough device capability check for the 3D scene.
 * 'none'  → no WebGL: show the static poster only
 * 'low'   → weak device or very small screen: poster only (saves battery and data)
 * 'mid'   → phones and tablets: simplified scene, DPR capped at 1.5
 * 'high'  → desktop: full scene, DPR capped at 2
 */
export function getDeviceTier() {
  if (typeof window === 'undefined') return 'none';
  let gl = null;
  try {
    const c = document.createElement('canvas');
    gl = c.getContext('webgl2') || c.getContext('webgl');
  } catch {
    gl = null;
  }
  if (!gl) return 'none';

  const cores = navigator.hardwareConcurrency || 4;
  const memory = navigator.deviceMemory || 4;
  const saveData = navigator.connection && navigator.connection.saveData;
  const narrow = window.innerWidth < 360;

  if (saveData || cores <= 2 || memory <= 2 || narrow) return 'low';
  if (window.innerWidth < 1024 || matchMedia('(pointer: coarse)').matches) return 'mid';
  return 'high';
}
