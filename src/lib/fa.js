const FA = '۰۱۲۳۴۵۶۷۸۹';
const AR = '٠١٢٣٤٥٦٧٨٩';

export const faNum = (v) => String(v).replace(/\d/g, (d) => FA[d]);
export const toLatinDigits = (s) =>
  String(s)
    .replace(/[۰-۹]/g, (d) => String(FA.indexOf(d)))
    .replace(/[٠-٩]/g, (d) => String(AR.indexOf(d)));

/* برای جستجو: ی و ک عربی، اعراب و نیم‌فاصله یکسان می‌شوند */
export const normalize = (s) =>
  toLatinDigits(String(s || ''))
    .replace(/ي/g, 'ی')
    .replace(/ك/g, 'ک')
    .replace(/[ً-ٰٟ]/g, '')
    .replace(/‌/g, ' ')
    .replace(/\s+/g, ' ')
    .trim()
    .toLowerCase();

export function normalizePhone(value) {
  return toLatinDigits(value)
    .replace(/[\s\-()]/g, '')
    .replace(/^\+98/, '0')
    .replace(/^0098/, '0');
}

export function phoneError(raw) {
  const v = normalizePhone(raw);
  if (!v) return 'شمارهٔ موبایل را وارد کنید.';
  if (!/^09\d{9}$/.test(v)) return 'شماره باید ۱۱ رقم باشد و با ۰۹ شروع شود؛ مثل ۰۹۱۲۳۴۵۶۷۸۹.';
  return '';
}
