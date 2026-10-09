import { useEffect, useRef, useState } from 'react';
import { weeks } from '../data/pregnancy.js';
import { addDays, diffDays, formatJ, fromISO, toISO, today } from '../lib/jalali.js';
import { faNum } from '../lib/fa.js';
import { href, navigate } from '../lib/router.js';
import { useStored } from '../lib/store.js';
import Draft from '../components/Draft.jsx';
import Icon from '../components/Icon.jsx';
import JalaliDate from '../components/JalaliDate.jsx';
import PageHead from '../components/PageHead.jsx';
import './Pregnancy.css';

const TRI = { 1: 'سه‌ماههٔ اول', 2: 'سه‌ماههٔ دوم', 3: 'سه‌ماههٔ سوم' };

/* هفته و روز بارداری از اولین روز آخرین پریود */
export function pregnancyFrom(lmp) {
  if (!lmp) return null;
  const days = diffDays(today(), lmp);
  if (days < 0 || days > 44 * 7) return null;
  return { week: Math.min(40, Math.floor(days / 7) + 1), day: days % 7, due: addDays(lmp, 280), days };
}

export default function Pregnancy({ weekParam }) {
  const [profile, setProfile] = useStored('profile', {});
  const lmp = fromISO(profile.lmp);
  const mine = pregnancyFrom(lmp);
  const n = Math.min(40, Math.max(1, Number(weekParam) || mine?.week || 12));
  const w = weeks[n - 1];
  const strip = useRef(null);
  const [draftLmp, setDraftLmp] = useState(lmp);
  const calc = pregnancyFrom(draftLmp);

  useEffect(() => {
    // فقط خود نوار را می‌چرخانیم؛ scrollIntoView کل صفحه را هم جابه‌جا می‌کند
    const c = strip.current;
    const a = c?.querySelector('[aria-current="page"]');
    if (c && a) {
      const ar = a.getBoundingClientRect();
      const cr = c.getBoundingClientRect();
      c.scrollLeft += ar.left + ar.width / 2 - (cr.left + cr.width / 2);
    }
  }, [n]);

  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title="بارداری هفته‌به‌هفته"
          intro="هر هفته جنین چقدر بزرگ شده، بدن مادر چه تغییری می‌کند، چه آزمایشی لازم است و پدر چه کاری می‌تواند بکند."
          crumbs={[{ label: 'سلامت', path: '/health' }, { label: 'بارداری هفته‌به‌هفته' }]}
        />

        <nav className="weeks" aria-label="انتخاب هفته" ref={strip}>
          {weeks.map((x) => (
            <a
              key={x.week}
              href={href(`/pregnancy/${x.week}`)}
              className={`weeks__n ${mine?.week === x.week ? 'weeks__n--mine' : ''}`}
              aria-current={x.week === n ? 'page' : undefined}
            >
              <span className="sr-only">هفتهٔ </span>
              {faNum(x.week)}
            </a>
          ))}
        </nav>

        <div className="two-col preg">
          <article className="week" aria-labelledby="week-title">
            <header className="week__head">
              <p className="week__tri">{TRI[w.trimester]}</p>
              <h2 id="week-title" className="week__title">
                هفتهٔ {faNum(w.week)}
              </h2>
              {w.size ? (
                <p className="week__size">
                  جنین تقریباً اندازهٔ <strong>{w.size}</strong> است
                </p>
              ) : (
                <p className="week__size">هنوز جنینی شکل نگرفته؛ شمارش هفته‌ها از اولین روز آخرین پریود است.</p>
              )}
              {(w.length || w.weight) && (
                <dl className="week__nums">
                  {w.length && (
                    <div>
                      <dt>قد</dt>
                      <dd>{w.length}</dd>
                    </div>
                  )}
                  {w.weight && (
                    <div>
                      <dt>وزن</dt>
                      <dd>{w.weight}</dd>
                    </div>
                  )}
                </dl>
              )}
            </header>
            <section>
              <h3>
                <Icon name="Baby" size={20} />
                رشد جنین
              </h3>
              <p>{w.baby}</p>
            </section>
            <section>
              <h3>
                <Icon name="HeartPulse" size={20} />
                بدن مادر
              </h3>
              <p>{w.mother}</p>
            </section>
            <section>
              <h3>
                <Icon name="Stethoscope" size={20} />
                مراقبت و آزمایش‌ها
              </h3>
              <p>{w.care}</p>
            </section>
            <section>
              <h3>
                <Icon name="HeartHandshake" size={20} />
                نکته برای پدر
              </h3>
              <p>{w.father}</p>
            </section>
            <Draft />
            <nav className="week__pager" aria-label="هفتهٔ قبل و بعد">
              {n > 1 ? (
                <a className="btn btn--line" href={href(`/pregnancy/${n - 1}`)}>
                  <Icon name="ChevronRight" size={18} />
                  هفتهٔ {faNum(n - 1)}
                </a>
              ) : (
                <span />
              )}
              {n < 40 && (
                <a className="btn btn--line" href={href(`/pregnancy/${n + 1}`)}>
                  هفتهٔ {faNum(n + 1)}
                  <Icon name="ChevronLeft" size={18} />
                </a>
              )}
            </nav>
          </article>

          <aside className="panel stack calc" aria-labelledby="calc-title">
            <h2 id="calc-title" className="section-title">
              هفتهٔ بارداری من
            </h2>
            {mine && (
              <p className="calc__mine">
                امروز هفتهٔ <strong>{faNum(mine.week)}</strong>
                {mine.day ? ` و ${faNum(mine.day)} روز` : ''} هستید. تاریخ تقریبی زایمان: <strong>{formatJ(mine.due)}</strong>
              </p>
            )}
            <div className="field">
              <span className="label" id="lmp-label">
                اولین روز آخرین پریود
              </span>
              <JalaliDate id="lmp" value={draftLmp} onChange={setDraftLmp} yearsBack={1} />
            </div>
            {draftLmp && !calc && (
              <p className="error" role="alert">
                <Icon name="CircleAlert" size={18} />
                این تاریخ با بارداری فعلی جور درنمی‌آید؛ تاریخ را بررسی کنید.
              </p>
            )}
            {calc && (
              <div className="calc__result" role="status">
                <p>
                  هفتهٔ <strong>{faNum(calc.week)}</strong>
                  {calc.day ? ` و ${faNum(calc.day)} روز` : ''}
                </p>
                <p>
                  تاریخ تقریبی زایمان: <strong>{formatJ(calc.due)}</strong>
                </p>
              </div>
            )}
            <div className="actions">
              <button
                type="button"
                className="btn btn--star"
                disabled={!calc}
                onClick={() => {
                  setProfile({ ...profile, lmp: toISO(draftLmp), stage: 'pregnancy' });
                  navigate(`/pregnancy/${calc.week}`);
                }}
              >
                نشانم بده و یادم بماند
              </button>
            </div>
            <p className="hint">تاریخ فقط روی همین دستگاه ذخیره می‌شود. تاریخ زایمان تخمینی است و پزشک با سونوگرافی دقیق‌ترش می‌کند.</p>
          </aside>
        </div>
      </div>
    </main>
  );
}
