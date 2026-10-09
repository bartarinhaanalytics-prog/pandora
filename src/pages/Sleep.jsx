import { useId, useState } from 'react';
import { routineSteps, sleepNeeds } from '../data/tools.js';
import { toISO, today } from '../lib/jalali.js';
import { faNum, toLatinDigits } from '../lib/fa.js';
import { ageMonths, childList } from '../lib/kids.js';
import { href } from '../lib/router.js';
import { useStored } from '../lib/store.js';
import { ageText } from './Profile.jsx';
import ChildPicker from '../components/ChildPicker.jsx';
import Draft from '../components/Draft.jsx';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';
import './Tools.css';

const fmtTime = (mins) => {
  const m = ((mins % 1440) + 1440) % 1440;
  return `${faNum(Math.floor(m / 60))}:${faNum(String(m % 60).padStart(2, '0'))}`;
};

export default function Sleep() {
  const id = useId();
  const [profile] = useStored('profile', {});
  const kids = childList(profile);
  const [kidId, setKidId] = useState(kids[0]?.id);
  const kid = kids.find((k) => k.id === kidId);
  const [wake, setWake] = useState('07:00');
  const [steps, setSteps] = useStored('routineSteps', routineSteps);
  const [checks, setChecks] = useStored('routineChecks', {});
  const [newStep, setNewStep] = useState('');
  const night = toISO(today());
  const tonight = checks.night === night ? checks.done || [] : [];

  const months = kid?.birthDate ? ageMonths(kid.birthDate) : null;
  const need = months !== null ? sleepNeeds.find((s) => months >= s.fromMonths && months < s.toMonths) || sleepNeeds[sleepNeeds.length - 1] : null;

  // خواب شب = میانهٔ نیاز روزانه منهای چرت تقریبی
  let bedtime = null;
  if (need && months >= 4) {
    const total = (need.hours[0] + need.hours[1]) / 2;
    const nap = months < 18 ? 3 : months < 36 ? 2 : months < 60 ? 1 : 0;
    const [h, m] = toLatinDigits(wake).split(':').map(Number);
    bedtime = fmtTime(h * 60 + m - Math.round((total - nap) * 60));
  }

  const toggle = (s) => setChecks({ night, done: tonight.includes(s) ? tonight.filter((x) => x !== s) : [...tonight, s] });

  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title="روتین خواب کودک"
          intro="چند ساعت خواب برای سن بچه‌تان لازم است، کی بخوابد، و یک روال شبانهٔ ثابت که خوابیدن را آسان‌تر می‌کند."
          crumbs={[{ label: 'قصه و لالایی' }, { label: 'روتین خواب' }]}
        />
        <div className="stack" style={{ marginBottom: 'var(--s-5)' }}>
          <ChildPicker kids={kids} value={kidId} onChange={setKidId} />
        </div>
        <div className="two-col">
          <section className="panel stack" aria-labelledby={`${id}-n`}>
            <h2 id={`${id}-n`} className="section-title">
              {kid ? `خواب ${kid.name}${months !== null ? `، ${ageText(kid.birthDate)}` : ''}` : 'نیاز خواب بر اساس سن'}
            </h2>
            {need ? (
              <>
                <p className="big-num">
                  {faNum(need.hours[0])} تا {faNum(need.hours[1])} ساعت
                </p>
                <p className="hint">در شبانه‌روز، با چرت‌ها ({need.label})</p>
                <dl className="facts">
                  <div>
                    <dt>چرت روزانه</dt>
                    <dd>{need.naps}</dd>
                  </div>
                  <div>
                    <dt>ساعت خواب معمول</dt>
                    <dd>{need.bedtime}</dd>
                  </div>
                </dl>
                {months >= 4 && (
                  <div className="field">
                    <label htmlFor={`${id}-w`}>صبح معمولاً چه ساعتی بیدار می‌شود؟</label>
                    <input id={`${id}-w`} type="time" className="input" value={wake} onChange={(e) => setWake(e.target.value)} style={{ maxWidth: 200 }} />
                    {bedtime && (
                      <p className="success">
                        <Icon name="Moon" size={18} />
                        <span>ساعت خواب پیشنهادی: حدود {bedtime}</span>
                      </p>
                    )}
                  </div>
                )}
                <p className="notice">
                  <Icon name="Info" size={18} />
                  <span>{need.tip}</span>
                </p>
              </>
            ) : (
              <ul className="rows" style={{ listStyle: 'none', padding: 0, margin: 0 }}>
                {sleepNeeds.map((s) => (
                  <li key={s.id} className="row">
                    <span className="row__title">{s.label}</span>
                    <span className="row__meta">
                      {faNum(s.hours[0])} تا {faNum(s.hours[1])} ساعت · {s.bedtime}
                    </span>
                  </li>
                ))}
              </ul>
            )}
            <Draft />
          </section>

          <aside className="panel stack" aria-labelledby={`${id}-r`}>
            <h2 id={`${id}-r`} className="section-title">
              روال امشب
            </h2>
            <ul className="routine">
              {steps.map((s) => (
                <li key={s}>
                  <label className="check">
                    <input type="checkbox" checked={tonight.includes(s)} onChange={() => toggle(s)} />
                    <span>{s}</span>
                  </label>
                  <button type="button" className="linkish" onClick={() => setSteps(steps.filter((x) => x !== s))}>
                    <Icon name="X" size={16} />
                    <span className="sr-only">حذف {s}</span>
                  </button>
                </li>
              ))}
            </ul>
            <form
              className="actions"
              onSubmit={(e) => {
                e.preventDefault();
                const v = newStep.trim();
                if (v && !steps.includes(v)) setSteps([...steps, v]);
                setNewStep('');
              }}
            >
              <label htmlFor={`${id}-s`} className="sr-only">
                مرحلهٔ تازه
              </label>
              <input id={`${id}-s`} className="input" style={{ flex: 1, minWidth: 160 }} maxLength={30} placeholder="مثلاً آب خوردن" value={newStep} onChange={(e) => setNewStep(e.target.value)} />
              <button type="submit" className="btn btn--line">
                <Icon name="Plus" size={18} />
                افزودن
              </button>
            </form>
            {tonight.length === steps.length && steps.length > 0 && (
              <p className="success" role="status">
                <Icon name="Check" size={18} />
                روال امشب کامل شد. شب بخیر!
              </p>
            )}
            <div className="actions">
              <a className="btn btn--star" href={href('/story')}>
                <Icon name="BookHeart" size={18} />
                قصهٔ امشب
              </a>
              <a className="btn btn--line" href={href('/lullabies')}>
                <Icon name="Music" size={18} />
                لالایی
              </a>
            </div>
          </aside>
        </div>
      </div>
    </main>
  );
}
