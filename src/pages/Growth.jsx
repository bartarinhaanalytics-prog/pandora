import { useId, useState } from 'react';
import { growthRef } from '../data/tools.js';
import { diffDays, formatJ, fromISO, toISO, today } from '../lib/jalali.js';
import { faNum, toLatinDigits } from '../lib/fa.js';
import { ageMonths, childList } from '../lib/kids.js';
import { uid, useStored } from '../lib/store.js';
import ChildPicker from '../components/ChildPicker.jsx';
import Draft from '../components/Draft.jsx';
import Icon from '../components/Icon.jsx';
import JalaliDate from '../components/JalaliDate.jsx';
import PageHead from '../components/PageHead.jsx';
import './Tools.css';

const W = 640;
const H = 340;
const PAD = { l: 44, r: 16, t: 16, b: 34 };
const METRIC = { weight: { label: 'وزن', unit: 'کیلوگرم' }, height: { label: 'قد', unit: 'سانتی‌متر' } };

function Chart({ sex, metric, points }) {
  const ref = growthRef[sex][metric];
  const months = growthRef.months;
  const maxM = 60;
  const lo = Math.floor(Math.min(...ref.p3, ...points.map((p) => p.v)) * 0.95);
  const hi = Math.ceil(Math.max(...ref.p97, ...points.map((p) => p.v)) * 1.03);
  const x = (m) => PAD.l + (m / maxM) * (W - PAD.l - PAD.r);
  const y = (v) => H - PAD.b - ((v - lo) / (hi - lo)) * (H - PAD.t - PAD.b);
  const line = (arr) => arr.map((v, i) => `${i ? 'L' : 'M'}${x(months[i]).toFixed(1)},${y(v).toFixed(1)}`).join(' ');
  const band = `${line(ref.p97)} ${[...ref.p3].reverse().map((v, i) => `L${x(months[months.length - 1 - i]).toFixed(1)},${y(v).toFixed(1)}`).join(' ')} Z`;
  const step = metric === 'weight' ? (hi - lo > 20 ? 5 : 2) : 10;
  const ticks = [];
  for (let v = Math.ceil(lo / step) * step; v <= hi; v += step) ticks.push(v);
  return (
    <div dir="ltr">
      <svg className="growth-chart" viewBox={`0 0 ${W} ${H}`} role="img" aria-label={`نمودار ${METRIC[metric].label} بر اساس سن`}>
        {ticks.map((v) => (
          <g key={v}>
            <line x1={PAD.l} x2={W - PAD.r} y1={y(v)} y2={y(v)} stroke="rgb(238 240 250 / 0.08)" />
            <text x={PAD.l - 8} y={y(v) + 4} textAnchor="end">
              {faNum(v)}
            </text>
          </g>
        ))}
        {[0, 12, 24, 36, 48, 60].map((m) => (
          <text key={m} x={x(m)} y={H - 10} textAnchor="middle">
            {m === 0 ? 'تولد' : `${faNum(m / 12)} سال`}
          </text>
        ))}
        <path d={band} fill="rgb(201 214 255 / 0.12)" />
        <path d={line(ref.p50)} fill="none" stroke="var(--halo)" strokeWidth="1.5" strokeDasharray="5 5" />
        {points.length > 1 && (
          <path
            d={points.map((p, i) => `${i ? 'L' : 'M'}${x(p.m).toFixed(1)},${y(p.v).toFixed(1)}`).join(' ')}
            fill="none"
            stroke="var(--star)"
            strokeWidth="2.5"
          />
        )}
        {points.map((p) => (
          <circle key={p.id} cx={x(p.m)} cy={y(p.v)} r="5" fill="var(--star)" stroke="var(--panel)" strokeWidth="2" />
        ))}
      </svg>
    </div>
  );
}

export default function Growth() {
  const id = useId();
  const [profile, setProfile] = useStored('profile', {});
  const kids = childList(profile);
  const [kidId, setKidId] = useState(kids[0]?.id);
  const kid = kids.find((k) => k.id === kidId);
  const [all, setAll] = useStored('growth', {});
  const [metric, setMetric] = useState('weight');
  const [form, setForm] = useState({ date: today(), weight: '', height: '' });
  const [err, setErr] = useState('');
  const list = (kid && all[kid.id]) || [];
  const sex = kid?.sex;

  const setSex = (s) => setProfile({ ...profile, children: (profile.children || []).map((c) => (c.id === kid.id ? { ...c, sex: s } : c)) });

  const add = (e) => {
    e.preventDefault();
    const w = parseFloat(toLatinDigits(form.weight).replace('٫', '.').replace('/', '.'));
    const h = parseFloat(toLatinDigits(form.height).replace('٫', '.').replace('/', '.'));
    if (!form.date || (!w && !h)) {
      setErr('تاریخ و دست‌کم وزن یا قد را وارد کنید.');
      return;
    }
    if ((w && (w < 1 || w > 40)) || (h && (h < 40 || h > 130))) {
      setErr('عددها درست به نظر نمی‌رسند؛ وزن به کیلوگرم و قد به سانتی‌متر.');
      return;
    }
    if (kid.birthDate && diffDays(form.date, kid.birthDate) < 0) {
      setErr('تاریخ اندازه‌گیری قبل از تولد است.');
      return;
    }
    setErr('');
    const next = [...list, { id: uid(), date: toISO(form.date), weight: w || null, height: h || null }].sort((a, b) => (a.date < b.date ? -1 : 1));
    setAll({ ...all, [kid.id]: next });
    setForm({ date: today(), weight: '', height: '' });
  };

  const points = kid?.birthDate
    ? list
        .filter((p) => p[metric])
        .map((p) => ({ id: p.id, m: ageMonths(kid.birthDate, fromISO(p.date)), v: p[metric] }))
        .filter((p) => p.m >= 0 && p.m <= 60)
    : [];

  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title="نمودار رشد کودک"
          intro="قد و وزن بچه را ثبت کنید و با محدودهٔ طبیعی سازمان جهانی بهداشت مقایسه کنید."
          crumbs={[{ label: 'ابزارها' }, { label: 'نمودار رشد' }]}
        />
        <div className="stack" style={{ marginBottom: 'var(--s-5)' }}>
          <ChildPicker kids={kids} value={kidId} onChange={setKidId} />
        </div>
        {kid && (
          <div className="two-col">
            <section className="panel stack" aria-labelledby={`${id}-c`}>
              <div className="actions" style={{ justifyContent: 'space-between' }}>
                <h2 id={`${id}-c`} className="section-title" style={{ margin: 0 }}>
                  {METRIC[metric].label} {kid.name}
                </h2>
                <div className="chips" role="group" aria-label="معیار">
                  {Object.entries(METRIC).map(([k, v]) => (
                    <button key={k} type="button" className="chip" aria-pressed={metric === k} onClick={() => setMetric(k)}>
                      {v.label}
                    </button>
                  ))}
                </div>
              </div>
              {!kid.birthDate ? (
                <p className="hint">برای رسم نمودار، تاریخ تولد {kid.name} را در پروفایل وارد کنید.</p>
              ) : !sex ? (
                <fieldset className="field">
                  <legend>محدودهٔ طبیعی برای دختر و پسر فرق دارد؛ {kid.name}:</legend>
                  <div className="chips">
                    <button type="button" className="chip" onClick={() => setSex('girl')}>
                      دختر
                    </button>
                    <button type="button" className="chip" onClick={() => setSex('boy')}>
                      پسر
                    </button>
                  </div>
                </fieldset>
              ) : (
                <>
                  <Chart sex={sex} metric={metric} points={points} />
                  <ul className="chart-legend">
                    <li>
                      <span className="swatch" style={{ background: 'rgb(201 214 255 / 0.25)' }} />
                      محدودهٔ طبیعی (صدک ۳ تا ۹۷)
                    </li>
                    <li>
                      <span className="swatch" style={{ background: 'none', borderTop: '2px dashed var(--halo)', height: 0 }} />
                      میانه
                    </li>
                    <li>
                      <span className="swatch" style={{ background: 'var(--star)' }} />
                      {kid.name}
                    </li>
                  </ul>
                  {points.length === 0 && <p className="hint">هنوز اندازه‌ای ثبت نشده؛ از فرم کنار اولین اندازه را وارد کنید.</p>}
                </>
              )}
              <p className="hint">
                بیرون بودن از محدوده یا تغییر ناگهانی مسیر رشد را با پزشک کودک در میان بگذارید. یک اندازهٔ تنها به‌تنهایی چیزی را نشان نمی‌دهد.
              </p>
              <Draft>پیش‌نویس؛ عددهای مرجع تقریبی‌اند و باید با جدول رسمی سازمان جهانی بهداشت تطبیق داده شوند.</Draft>
            </section>
            <aside className="stack">
              <form className="panel stack" onSubmit={add} noValidate aria-labelledby={`${id}-f`}>
                <h2 id={`${id}-f`} className="section-title">
                  اندازهٔ تازه
                </h2>
                <div className="field">
                  <span className="label">تاریخ اندازه‌گیری</span>
                  <JalaliDate id={`${id}-d`} value={form.date} onChange={(d) => setForm({ ...form, date: d })} yearsBack={6} />
                </div>
                <div className="form-row">
                  <div className="field">
                    <label htmlFor={`${id}-w`}>وزن (کیلوگرم)</label>
                    <input id={`${id}-w`} className="input" inputMode="decimal" dir="ltr" value={form.weight} onChange={(e) => setForm({ ...form, weight: e.target.value })} placeholder="۹٫۵" />
                  </div>
                  <div className="field">
                    <label htmlFor={`${id}-h`}>قد (سانتی‌متر)</label>
                    <input id={`${id}-h`} className="input" inputMode="decimal" dir="ltr" value={form.height} onChange={(e) => setForm({ ...form, height: e.target.value })} placeholder="۷۵" />
                  </div>
                </div>
                {err && (
                  <p className="error" role="alert">
                    <Icon name="CircleAlert" size={18} />
                    {err}
                  </p>
                )}
                <button type="submit" className="btn btn--star">
                  <Icon name="Plus" size={18} />
                  ثبت
                </button>
              </form>
              {list.length > 0 && (
                <section className="panel" aria-label="سابقه">
                  <h2 className="section-title">سابقه</h2>
                  <ul className="rows" style={{ listStyle: 'none', padding: 0, margin: 0 }}>
                    {[...list].reverse().map((p) => (
                      <li key={p.id} className="row hist">
                        <span>
                          {formatJ(fromISO(p.date))}: {p.weight ? `${faNum(p.weight)} کیلو` : ''}
                          {p.weight && p.height ? '، ' : ''}
                          {p.height ? `${faNum(p.height)} سانت` : ''}
                        </span>
                        <button type="button" className="hist__del" onClick={() => setAll({ ...all, [kid.id]: list.filter((x) => x.id !== p.id) })}>
                          <Icon name="Trash2" size={18} />
                          <span className="sr-only">حذف</span>
                        </button>
                      </li>
                    ))}
                  </ul>
                </section>
              )}
            </aside>
          </div>
        )}
      </div>
    </main>
  );
}
