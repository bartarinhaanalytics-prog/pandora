import { useId, useState } from 'react';
import { addDays, diffDays, formatJ, fromISO, toISO, today } from '../lib/jalali.js';
import { faNum } from '../lib/fa.js';
import { href } from '../lib/router.js';
import { useStored } from '../lib/store.js';
import Icon from '../components/Icon.jsx';
import JalaliDate from '../components/JalaliDate.jsx';
import PageHead from '../components/PageHead.jsx';

const MILESTONES = [
  { from: 11 * 7, to: 13 * 7 + 6, label: 'غربالگری سه‌ماههٔ اول (NT)' },
  { from: 15 * 7, to: 17 * 7 + 6, label: 'غربالگری سه‌ماههٔ دوم' },
  { from: 18 * 7, to: 22 * 7 + 6, label: 'سونوگرافی آنومالی' },
  { from: 24 * 7, to: 28 * 7 + 6, label: 'آزمایش قند بارداری (GTT)' },
  { from: 30 * 7, to: 34 * 7 + 6, label: 'سونوگرافی سه‌ماههٔ سوم' },
  { from: 37 * 7, to: 37 * 7, label: 'رسیدن به ترم کامل (۳۷ هفته)' }
];

/* محاسبهٔ تاریخ زایمان از اولین روز آخرین پریود (با طول چرخه) یا تاریخ لقاح */
export default function DueDate() {
  const id = useId();
  const [profile, setProfile] = useStored('profile', {});
  const [method, setMethod] = useState('lmp');
  const [date, setDate] = useState(fromISO(profile.lmp));
  const [cycle, setCycle] = useState(28);
  const [saved, setSaved] = useState(false);

  // همه‌چیز به «اولین روز پریود معادل» تبدیل می‌شود
  const lmp = date ? (method === 'lmp' ? addDays(date, cycle - 28) : addDays(date, -14)) : null;
  const days = lmp ? diffDays(today(), lmp) : null;
  const valid = lmp && days >= 0 && days <= 44 * 7;
  const due = valid ? addDays(lmp, 280) : null;
  const week = valid ? Math.floor(days / 7) : 0;
  const tri = week < 14 ? 'سه‌ماههٔ اول' : week < 28 ? 'سه‌ماههٔ دوم' : 'سه‌ماههٔ سوم';

  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title="محاسبهٔ تاریخ زایمان"
          intro="با تاریخ آخرین پریود یا تاریخ لقاح، تاریخ تقریبی زایمان و زمان آزمایش‌های مهم را به تاریخ شمسی ببینید."
          crumbs={[{ label: 'ابزارها' }, { label: 'تاریخ زایمان' }]}
        />
        <div className="two-col">
          <section className="panel stack" aria-labelledby={`${id}-f`}>
            <h2 id={`${id}-f`} className="section-title">
              بر اساس
            </h2>
            <div className="chips" role="group" aria-label="روش محاسبه">
              <button type="button" className="chip" aria-pressed={method === 'lmp'} onClick={() => setMethod('lmp')}>
                آخرین پریود
              </button>
              <button type="button" className="chip" aria-pressed={method === 'conception'} onClick={() => setMethod('conception')}>
                تاریخ لقاح
              </button>
            </div>
            <div className="field">
              <span className="label">{method === 'lmp' ? 'اولین روز آخرین پریود' : 'تاریخ لقاح (یا انتقال جنین)'}</span>
              <JalaliDate id={`${id}-d`} value={date} onChange={setDate} yearsBack={1} />
            </div>
            {method === 'lmp' && (
              <div className="field">
                <label htmlFor={`${id}-c`}>طول معمول چرخه (روز)</label>
                <select id={`${id}-c`} className="select" value={cycle} onChange={(e) => setCycle(Number(e.target.value))}>
                  {Array.from({ length: 15 }, (_, i) => i + 21).map((n) => (
                    <option key={n} value={n}>
                      {faNum(n)}
                    </option>
                  ))}
                </select>
              </div>
            )}
            {date && !valid && (
              <p className="error" role="alert">
                <Icon name="CircleAlert" size={18} />
                این تاریخ با یک بارداری فعلی جور درنمی‌آید؛ تاریخ را بررسی کنید.
              </p>
            )}
          </section>

          <section className="panel stack" aria-live="polite" aria-labelledby={`${id}-r`}>
            <h2 id={`${id}-r`} className="section-title">
              {valid ? `زایمان حدود ${formatJ(due)}` : 'نتیجه'}
            </h2>
            {valid ? (
              <>
                <dl className="facts">
                  <div>
                    <dt>سن بارداری امروز</dt>
                    <dd>
                      {faNum(week)} هفته و {faNum(days % 7)} روز ({tri})
                    </dd>
                  </div>
                  <div>
                    <dt>روزهای مانده تا زایمان</dt>
                    <dd>{faNum(Math.max(0, diffDays(due, today())))} روز</dd>
                  </div>
                </dl>
                <ul className="rows" style={{ listStyle: 'none', padding: 0, margin: 0 }}>
                  {MILESTONES.map((m) => {
                    const a = addDays(lmp, m.from);
                    const b = addDays(lmp, m.to);
                    const past = diffDays(today(), b) > 0;
                    return (
                      <li key={m.label} className="row">
                        <span className="row__title" style={past ? { color: 'var(--moon-3)' } : undefined}>
                          {m.label}
                        </span>
                        <span className="row__meta">
                          {m.from === m.to ? formatJ(a) : `${formatJ(a, false)} تا ${formatJ(b)}`}
                          {past && <span className="tag">گذشته</span>}
                        </span>
                      </li>
                    );
                  })}
                </ul>
                <div className="actions">
                  <a className="btn btn--line" href={href(`/pregnancy/${Math.min(40, week + 1)}`)}>
                    این هفته چه خبر است؟
                  </a>
                  <button
                    type="button"
                    className="btn btn--star"
                    onClick={() => {
                      setProfile({ ...profile, lmp: toISO(lmp), stage: 'pregnancy' });
                      setSaved(true);
                    }}
                  >
                    {saved ? 'ذخیره شد' : 'یادم بماند'}
                  </button>
                </div>
              </>
            ) : (
              <p className="hint">تاریخ را وارد کنید تا تاریخ زایمان و زمان آزمایش‌ها نشان داده شود.</p>
            )}
            <p className="hint">زمان دقیق آزمایش‌ها را پزشک یا ماما تعیین می‌کند؛ این‌ها بازه‌های معمول‌اند.</p>
          </section>
        </div>
      </div>
    </main>
  );
}
