/*
 * تاریخ شمسی با Intl مرورگر (تقویم persian)؛ بدون کتابخانهٔ جدا.
 * همهٔ تاریخ‌ها ظهر محلی ساخته می‌شوند تا تغییر ساعت تابستانی روز را جابه‌جا نکند.
 */
import { faNum } from './fa.js';

const fmt = new Intl.DateTimeFormat('en-u-ca-persian-nu-latn', { year: 'numeric', month: 'numeric', day: 'numeric' });

export const MONTHS = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
export const WEEKDAYS = ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج']; // شنبه تا جمعه

export const noon = (d) => new Date(d.getFullYear(), d.getMonth(), d.getDate(), 12);
export const today = () => noon(new Date());
export const addDays = (d, n) => noon(new Date(d.getFullYear(), d.getMonth(), d.getDate() + n, 12));
export const diffDays = (a, b) => Math.round((noon(a) - noon(b)) / 86400000);

export function toJ(date) {
  const parts = fmt.formatToParts(date);
  const get = (t) => Number(parts.find((p) => p.type === t).value);
  return { y: get('year'), m: get('month'), d: get('day') };
}

/* شمسی به میلادی: از تخمین شروع می‌کنیم و چند روز جلو و عقب می‌رویم */
export function fromJ(y, m, d) {
  const guess = new Date(y + 621, m + 1, d, 12); // تقریب ±۱ ماه
  let cur = addDays(guess, -45);
  for (let i = 0; i < 120; i += 1) {
    const j = toJ(cur);
    if (j.y === y && j.m === m && j.d === d) return cur;
    cur = addDays(cur, 1);
  }
  return null;
}

export function monthLength(y, m) {
  if (m <= 6) return 31;
  if (m <= 11) return 30;
  return fromJ(y, 12, 30) ? 30 : 29;
}

/* ۰ = شنبه */
export const weekdayIndex = (date) => (date.getDay() + 1) % 7;

export function formatJ(date, withYear = true) {
  const j = toJ(date);
  return `${faNum(j.d)} ${MONTHS[j.m - 1]}${withYear ? ` ${faNum(j.y)}` : ''}`;
}

export const toISO = (d) => `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
export const fromISO = (s) => {
  if (!s) return null;
  const [y, m, d] = s.split('-').map(Number);
  return new Date(y, m - 1, d, 12);
};
