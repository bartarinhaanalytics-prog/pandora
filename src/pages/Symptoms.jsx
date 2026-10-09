import { useId, useState } from 'react';
import { symptoms } from '../data/tools.js';
import { addDays, formatJ, fromISO, toISO, today } from '../lib/jalali.js';
import { useStored } from '../lib/store.js';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';
import './Tools.css';

const GROUPS = [
  { id: 'body', label: 'بدن' },
  { id: 'pregnancy', label: 'بارداری' },
  { id: 'mood', label: 'حال و حوصله' }
];

export default function Symptoms() {
  const id = useId();
  const [log, setLog] = useStored('symptomLog', {});
  const [day, setDay] = useState(toISO(today()));
  const entry = log[day] || { ids: [], note: '' };
  const set = (patch) => setLog({ ...log, [day]: { ...entry, ...patch } });
  const toggle = (sid) => set({ ids: entry.ids.includes(sid) ? entry.ids.filter((x) => x !== sid) : [...entry.ids, sid] });
  const warns = symptoms.filter((s) => entry.ids.includes(s.id) && s.warn);
  const days = Array.from({ length: 7 }, (_, i) => addDays(today(), -i));
  const history = Object.entries(log)
    .filter(([d, e]) => d !== day && (e.ids.length || e.note))
    .sort((a, b) => (a[0] < b[0] ? 1 : -1))
    .slice(0, 14);
  const label = (sid) => symptoms.find((s) => s.id === sid)?.label || sid;

  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title="ثبت علائم روزانه"
          intro="هر روز چند ثانیه: چه حسی دارید و بدنتان چه می‌گوید. سابقه‌اش برای مراجعه به پزشک به کار می‌آید."
          crumbs={[{ label: 'ابزارها' }, { label: 'علائم روزانه' }]}
        />
        <div className="chips" role="group" aria-label="روز" style={{ marginBottom: 'var(--s-5)' }}>
          {days.map((d, i) => (
            <button key={toISO(d)} type="button" className="chip" aria-pressed={day === toISO(d)} onClick={() => setDay(toISO(d))}>
              {i === 0 ? 'امروز' : i === 1 ? 'دیروز' : formatJ(d, false)}
            </button>
          ))}
        </div>
        <div className="two-col">
          <section className="panel stack" aria-labelledby={`${id}-t`}>
            <h2 id={`${id}-t`} className="section-title">
              {formatJ(fromISO(day))}
            </h2>
            {GROUPS.map((g) => (
              <fieldset key={g.id} className="field sym-group">
                <legend>{g.label}</legend>
                <div className="chips">
                  {symptoms
                    .filter((s) => s.group === g.id)
                    .map((s) => (
                      <button key={s.id} type="button" className="chip" aria-pressed={entry.ids.includes(s.id)} onClick={() => toggle(s.id)}>
                        {s.label}
                      </button>
                    ))}
                </div>
              </fieldset>
            ))}
            <div className="field">
              <label htmlFor={`${id}-n`}>یادداشت</label>
              <textarea id={`${id}-n`} className="textarea" maxLength={600} value={entry.note} onChange={(e) => set({ note: e.target.value })} />
            </div>
            <p className="hint" role="status">
              خودکار ذخیره می‌شود؛ فقط روی همین دستگاه.
            </p>
          </section>
          <aside className="stack">
            {warns.length > 0 && (
              <section className="panel sym-warn" aria-label="هشدار">
                {warns.map((s) => (
                  <p key={s.id} className="notice notice--warn">
                    <Icon name="CircleAlert" size={18} />
                    <span>
                      <strong>{s.label}:</strong> {s.warn}
                    </span>
                  </p>
                ))}
                <p className="hint">
                  اورژانس:{' '}
                  <a href="tel:115" className="num">
                    ۱۱۵
                  </a>
                </p>
              </section>
            )}
            <section className="panel" aria-label="روزهای قبل">
              <h2 className="section-title">روزهای قبل</h2>
              {history.length ? (
                <ul className="rows" style={{ listStyle: 'none', padding: 0, margin: 0 }}>
                  {history.map(([d, e]) => (
                    <li key={d} className="row">
                      <span className="row__title">{formatJ(fromISO(d))}</span>
                      <span className="row__meta">{e.ids.map(label).join('، ')}</span>
                      {e.note && <p>{e.note}</p>}
                    </li>
                  ))}
                </ul>
              ) : (
                <p className="hint">هنوز روز دیگری ثبت نشده.</p>
              )}
            </section>
          </aside>
        </div>
      </div>
    </main>
  );
}
