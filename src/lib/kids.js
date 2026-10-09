import { diffDays, fromISO, today } from './jalali.js';

/* سن به ماه (اعشاری) در یک تاریخ */
export const ageMonths = (birth, at = today()) => diffDays(at, birth) / 30.4375;

export function childList(profile) {
  return (profile.children || []).filter((k) => k.name).map((k) => ({ ...k, birthDate: fromISO(k.birth) }));
}
