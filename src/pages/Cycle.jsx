import { useMemo, useState } from 'react';
import { MONTHS, WEEKDAYS, addDays, diffDays, formatJ, fromISO, fromJ, monthLength, toISO, toJ, today, weekdayIndex } from '../lib/jalali.js';
import { faNum } from '../lib/fa.js';
import { useStored } from '../lib/store.js';
import Icon from '../components/Icon.jsx';
import JalaliDate from '../components/JalaliDate.jsx';
import PageHead from '../components/PageHead.jsx';
import './Cycle.css';

const DEFAULT = { cycleLen: 28, periodLen: 5, periods: [] };

/* میانگین طول چرخه از پریودهای ثبت‌شده (اگر دست‌کم دو شروع داریم) */
function averageCycle(starts, fallback) {
  if (starts.length < 2) return fallback;
  const gaps = [];
  for (let i = 1; i < starts.length; i += 1) {
    const g = diffDays(starts[i], starts[i - 1]);
    if (g >= 18 && g <= 45) gaps.push(g);
  }
  if (!gaps.length) return fallback;
  return Math.round(gaps.slice(-6).reduce((a, b) => a + b, 0) / Math.min(gaps.length, 6));
}

export function buildPlan(data) {
  const starts = data.periods.map((p) => fromISO(p.start)).sort((a, b) => a - b);
  if (!starts.length) return null;
  const len = averageCycle(starts, data.cycleLen);
  const last = starts[starts.length - 1];
  // پریود بعدی: اولین شروع پیش‌بینی‌شده که از امروز گذشته نباشد
  let next = addDays(last, len);
  while (diffDays(next, today()) < -2) next = addDays(next, len);
  const cycles = [];
  for (let k = -1; k < 4; k += 1) {
    const s = addDays(next, k * len);
    const ov = addDays(s, -14);
    cycles.push({ start: s, ovulation: ov, fertileFrom: addDays(ov, -5), fertileTo: addDays(ov, 1) });
  }
  return { len, last, next, cycles };
}

function dayKind(date, data, plan) {
  const iso = toISO(date);
  for (const p of data.periods) {
    const s = fromISO(p.start);
    const e = p.end ? fromISO(p.end) : addDays(s, data.periodLen - 1);
    if (date >= s && date <= e) return 'period';
  }
  if (!plan) return '';
  for (const c of plan.cycles) {
    if (date >= c.start && date <= addDays(c.start, data.periodLen - 1) && c.start > plan.last) return 'predicted';
    if (toISO(c.ovulation) === iso) return 'ovulation';
    if (date >= c.fertileFrom && date <= c.fertileTo) return 'fertile';
  }
  return '';
}

export default function Cycle() {
  const [data, setData] = useStored('cycle', DEFAULT);
  const [first, setFirst] = useState(null);
  const now = toJ(today());
  const [view, setView] = useState({ y: now.y, m: now.m });
  const plan = useMemo(() => buildPlan(data), [data]);
  const open = data.periods.find((p) => !p.end);

  const save = (patch) => setData({ ...DEFAULT, ...data, ...patch });
  const startToday = () => {
    const t = toISO(today());
    if (data.periods.some((p) => p.start === t)) return;
    save({ periods: [...data.periods.filter((p) => p.end || diffDays(today(), fromISO(p.start)) > 12), { start: t }].sort((a, b) => (a.start < b.start ? -1 : 1)) });
  };
  const endToday = () => {
    if (!open) return;
    save({ periods: data.periods.map((p) => (p === open ? { ...p, end: toISO(today()) } : p)) });
  };
  const removePeriod = (start) => save({ periods: data.periods.filter((p) => p.start !== start) });

  const move = (d) => {
    let { y, m } = view;
    m += d;
    if (m < 1) {
      m = 12;
      y -= 1;
    }
    if (m > 12) {
      m = 1;
      y += 1;
    }
    setView({ y, m });
  };

  const firstDay = fromJ(view.y, view.m, 1);
  const lead = weekdayIndex(firstDay);
  const len = monthLength(view.y, view.m);
  const cells = [];
  for (let i = 0; i < lead; i += 1) cells.push(null);
  for (let d = 1; d <= len; d += 1) cells.push(addDays(firstDay, d - 1));

  const until = plan ? diffDays(plan.next, today()) : null;
  const fertileNow = plan?.cycles.find((c) => today() >= c.fertileFrom && today() <= c.fertileTo);
  const upcomingFertile = plan?.cycles.find((c) => c.fertileTo >= today());

  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title="تقویم قاعدگی و باروری"
          intro="روزهای پریود را ثبت کنید تا پریود بعدی و روزهای باروری را با تقویم شمسی پیش‌بینی کنیم."
          crumbs={[{ label: 'ابزارها' }, { label: 'تقویم قاعدگی' }]}
        />

        {!data.periods.length ? (
          <section className="panel stack setup" aria-labelledby="setup-title">
            <h2 id="setup-title" className="section-title">
              اولین پریودتان را ثبت کنید
            </h2>
            <div className="field">
              <span className="label" id="first-label">
                اولین روز آخرین پریود
              </span>
              <JalaliDate id="first" value={first} onChange={setFirst} yearsBack={1} />
            </div>
            <div className="form-row">
              <div className="field">
                <label htmlFor="cl">طول چرخه (روز)</label>
                <select id="cl" className="select" value={data.cycleLen} onChange={(e) => save({ cycleLen: Number(e.target.value) })}>
                  {Array.from({ length: 25 }, (_, i) => i + 21).map((n) => (
                    <option key={n} value={n}>
                      {faNum(n)}
                    </option>
                  ))}
                </select>
                <span className="hint">اگر نمی‌دانید، ۲۸ بماند.</span>
              </div>
              <div className="field">
                <label htmlFor="pl">چند روز طول می‌کشد</label>
                <select id="pl" className="select" value={data.periodLen} onChange={(e) => save({ periodLen: Number(e.target.value) })}>
                  {Array.from({ length: 9 }, (_, i) => i + 2).map((n) => (
                    <option key={n} value={n}>
                      {faNum(n)}
                    </option>
                  ))}
                </select>
              </div>
            </div>
            <div className="actions">
              <button
                type="button"
                className="btn btn--star"
                disabled={!first || first > today()}
                onClick={() => save({ periods: [{ start: toISO(first) }].map((p) => ({ ...p, end: toISO(addDays(first, data.periodLen - 1)) })) })}
              >
                ساختن تقویم
              </button>
            </div>
          </section>
        ) : (
          <div className="two-col cycle">
            <section className="panel cal" aria-labelledby="cal-title">
              <div className="cal__head">
                <button type="button" className="cal__nav" onClick={() => move(-1)}>
                  <Icon name="ChevronRight" size={20} />
                  <span className="sr-only">ماه قبل</span>
                </button>
                <h2 id="cal-title" className="cal__title" aria-live="polite">
                  {MONTHS[view.m - 1]} {faNum(view.y)}
                </h2>
                <button type="button" className="cal__nav" onClick={() => move(1)}>
                  <Icon name="ChevronLeft" size={20} />
                  <span className="sr-only">ماه بعد</span>
                </button>
              </div>
              <div className="cal__grid" role="grid" aria-labelledby="cal-title">
                <div role="row" className="cal__row">
                  {WEEKDAYS.map((d) => (
                    <span key={d} role="columnheader" className="cal__wd">
                      {d}
                    </span>
                  ))}
                </div>
                {Array.from({ length: Math.ceil(cells.length / 7) }, (_, r) => (
                  <div role="row" className="cal__row" key={r}>
                    {cells.slice(r * 7, r * 7 + 7).map((c, i) => {
                      if (!c) return <span key={`e${i}`} role="gridcell" className="cal__day cal__day--empty" />;
                      const kind = dayKind(c, data, plan);
                      const isToday = diffDays(c, today()) === 0;
                      const label = { period: 'پریود', predicted: 'پریود پیش‌بینی‌شده', fertile: 'روز باروری', ovulation: 'تخمک‌گذاری' }[kind];
                      return (
                        <span
                          key={toISO(c)}
                          role="gridcell"
                          className={`cal__day ${kind ? `cal__day--${kind}` : ''} ${isToday ? 'cal__day--today' : ''}`}
                          aria-label={`${formatJ(c, false)}${label ? `، ${label}` : ''}${isToday ? '، امروز' : ''}`}
                        >
                          {faNum(toJ(c).d)}
                        </span>
                      );
                    })}
                  </div>
                ))}
              </div>
              <ul className="cal__legend">
                <li>
                  <span className="dot dot--period" />
                  پریود
                </li>
                <li>
                  <span className="dot dot--predicted" />
                  پریود پیش‌بینی‌شده
                </li>
                <li>
                  <span className="dot dot--fertile" />
                  روزهای باروری
                </li>
                <li>
                  <span className="dot dot--ovulation" />
                  تخمک‌گذاری
                </li>
              </ul>
            </section>

            <aside className="stack">
              <section className="panel stack" aria-labelledby="sum-title">
                <h2 id="sum-title" className="section-title">
                  {open ? `روز ${faNum(diffDays(today(), fromISO(open.start)) + 1)} پریود` : until === 0 ? 'پریود امروز پیش‌بینی می‌شود' : until > 0 ? `${faNum(until)} روز تا پریود بعدی` : 'پریود کمی دیر شده'}
                </h2>
                <dl className="facts">
                  <div>
                    <dt>پریود بعدی</dt>
                    <dd>{formatJ(plan.next)}</dd>
                  </div>
                  {upcomingFertile && (
                    <div>
                      <dt>{fertileNow ? 'الان در روزهای باروری هستید' : 'روزهای باروری بعدی'}</dt>
                      <dd>
                        {formatJ(upcomingFertile.fertileFrom, false)} تا {formatJ(upcomingFertile.fertileTo, false)}
                      </dd>
                    </div>
                  )}
                  <div>
                    <dt>طول چرخه</dt>
                    <dd>
                      {faNum(plan.len)} روز {data.periods.length > 1 ? '(میانگین ثبت‌های شما)' : ''}
                    </dd>
                  </div>
                </dl>
                <div className="actions">
                  {open ? (
                    <button type="button" className="btn btn--star" onClick={endToday}>
                      پریودم امروز تمام شد
                    </button>
                  ) : (
                    <button type="button" className="btn btn--star" onClick={startToday}>
                      <Icon name="Droplet" size={18} />
                      پریودم امروز شروع شد
                    </button>
                  )}
                </div>
                {until !== null && until < -7 && !open && (
                  <p className="notice notice--warn">
                    <Icon name="Info" size={18} />
                    <span>اگر پریود بیش از یک هفته دیر شده و احتمال بارداری هست، تست بارداری بدهید. اگر چند ماه پشت‌سرهم نامنظم است، با پزشک مشورت کنید.</span>
                  </p>
                )}
              </section>

              <section className="panel" aria-labelledby="hist-title">
                <h2 id="hist-title" className="section-title">
                  پریودهای ثبت‌شده
                </h2>
                <ul className="rows">
                  {[...data.periods].reverse().map((p) => (
                    <li key={p.start} className="row hist">
                      <span>
                        {formatJ(fromISO(p.start))}
                        {p.end ? ` تا ${formatJ(fromISO(p.end), false)}` : ' (ادامه دارد)'}
                      </span>
                      <button type="button" className="hist__del" onClick={() => removePeriod(p.start)}>
                        <Icon name="Trash2" size={18} />
                        <span className="sr-only">حذف این پریود</span>
                      </button>
                    </li>
                  ))}
                </ul>
              </section>

              <p className="notice">
                <Icon name="Info" size={18} />
                <span>
                  پیش‌بینی‌ها تقریبی‌اند و برای پیشگیری از بارداری به آن‌ها تکیه نکنید. داده‌ها فقط روی همین دستگاه ذخیره می‌شوند.
                </span>
              </p>
            </aside>
          </div>
        )}
      </div>
    </main>
  );
}
