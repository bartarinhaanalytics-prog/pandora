import { useState } from 'react';
import { vaccines, vaccinesNote } from '../data/tools.js';
import { addDays, diffDays, formatJ, fromISO, today } from '../lib/jalali.js';
import { childList } from '../lib/kids.js';
import { useStored } from '../lib/store.js';
import ChildPicker from '../components/ChildPicker.jsx';
import Draft from '../components/Draft.jsx';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';
import './Tools.css';

const due = (birth, months) => addDays(birth, Math.round(months * 30.4375));

export function nextVaccine(kid, done = {}) {
  if (!kid?.birthDate) return null;
  const v = vaccines.find((x) => !done[`${kid.id}:${x.id}`] && diffDays(due(kid.birthDate, x.ageMonths), today()) >= -60);
  return v ? { ...v, date: due(kid.birthDate, v.ageMonths) } : null;
}

export default function Vaccines() {
  const [profile] = useStored('profile', {});
  const kids = childList(profile);
  const [kidId, setKidId] = useState(kids[0]?.id);
  const [done, setDone] = useStored('vaccinesDone', {});
  const kid = kids.find((k) => k.id === kidId);
  const birth = kid?.birthDate;
  const next = nextVaccine(kid, done);

  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title="جدول واکسن کودک"
          intro="برنامهٔ کشوری واکسیناسیون از تولد تا ۶ سالگی، با تاریخ شمسی هر نوبت برای بچهٔ شما."
          crumbs={[{ label: 'ابزارها' }, { label: 'جدول واکسن' }]}
        />
        <div className="stack" style={{ marginBottom: 'var(--s-6)' }}>
          <ChildPicker kids={kids} value={kidId} onChange={setKidId} />
          {kid && !birth && <p className="hint">برای دیدن تاریخ‌ها، تاریخ تولد {kid.name} را در پروفایل وارد کنید.</p>}
          {next && (
            <p className="notice notice--warn">
              <Icon name="CalendarDays" size={18} />
              <span>
                نوبت بعدی {kid.name}: <strong>{next.label}</strong>، حدود {formatJ(next.date)}
              </span>
            </p>
          )}
        </div>
        <div className="two-col">
          <ol className="vax">
            {vaccines.map((v) => {
              const key = kid ? `${kid.id}:${v.id}` : '';
              const date = birth ? due(birth, v.ageMonths) : null;
              const isDone = key && done[key];
              const late = date && !isDone && diffDays(today(), date) > 14;
              return (
                <li key={v.id} className={`vax__item ${isDone ? 'is-done' : ''}`}>
                  <div className="vax__head">
                    <h2 className="vax__age">{v.label}</h2>
                    {date && <span className="row__meta">{formatJ(date)}</span>}
                    {late && <span className="tag">زمانش گذشته؛ با مرکز بهداشت هماهنگ کنید</span>}
                  </div>
                  <ul className="vax__shots">
                    {v.shots.map((s) => (
                      <li key={s.name}>
                        <strong>{s.name}</strong>
                        {s.note && <span>{s.note}</span>}
                      </li>
                    ))}
                  </ul>
                  {kid && (
                    <label className="check">
                      <input type="checkbox" checked={Boolean(isDone)} onChange={(e) => setDone({ ...done, [key]: e.target.checked })} />
                      <span>زده شد</span>
                    </label>
                  )}
                </li>
              );
            })}
          </ol>
          <aside className="panel stack">
            <h2 className="section-title">بدانید</h2>
            <p className="hint" style={{ fontSize: '1rem', color: 'var(--moon-2)' }}>
              {vaccinesNote}
            </p>
            <Draft>پیش‌نویس؛ برنامه را با کارت واکسن و مرکز بهداشت تطبیق دهید.</Draft>
          </aside>
        </div>
      </div>
    </main>
  );
}
