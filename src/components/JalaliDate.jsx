import { MONTHS, fromJ, monthLength, toJ, today } from '../lib/jalali.js';
import { faNum } from '../lib/fa.js';

/*
 * انتخاب تاریخ شمسی با سه فهرست (روز، ماه، سال). مقدار: Date یا null.
 * yearsBack/yearsAhead بازهٔ سال‌ها را نسبت به امسال تعیین می‌کند.
 */
export default function JalaliDate({ id, value, onChange, yearsBack = 2, yearsAhead = 0, invalid, describedBy }) {
  const base = toJ(today());
  const cur = value ? toJ(value) : null;
  const years = [];
  for (let y = base.y + yearsAhead; y >= base.y - yearsBack; y -= 1) years.push(y);

  const emit = (y, m, d) => {
    if (!y || !m || !d) {
      onChange(null);
      return;
    }
    const max = monthLength(y, m);
    onChange(fromJ(y, m, Math.min(d, max)));
  };
  const y = cur?.y ?? '';
  const m = cur?.m ?? '';
  const d = cur?.d ?? '';
  const pick = (part) => (e) => {
    const v = Number(e.target.value) || '';
    const next = { y: y || base.y, m: m || base.m, d: d || 1, [part]: v };
    emit(next.y, next.m, next.d);
  };
  const days = monthLength(y || base.y, m || base.m);

  return (
    <div className="jdate" role="group" aria-labelledby={id ? `${id}-label` : undefined} aria-describedby={describedBy}>
      <select className="select" aria-label="روز" value={d} onChange={pick('d')} aria-invalid={invalid || undefined}>
        <option value="">روز</option>
        {Array.from({ length: days }, (_, i) => i + 1).map((n) => (
          <option key={n} value={n}>
            {faNum(n)}
          </option>
        ))}
      </select>
      <select className="select" aria-label="ماه" value={m} onChange={pick('m')} aria-invalid={invalid || undefined}>
        <option value="">ماه</option>
        {MONTHS.map((name, i) => (
          <option key={name} value={i + 1}>
            {name}
          </option>
        ))}
      </select>
      <select className="select" aria-label="سال" value={y} onChange={pick('y')} aria-invalid={invalid || undefined}>
        <option value="">سال</option>
        {years.map((n) => (
          <option key={n} value={n}>
            {faNum(n)}
          </option>
        ))}
      </select>
    </div>
  );
}
