import { useId, useState } from 'react';
import { stages } from '../data/stages.js';
import { toISO } from '../lib/jalali.js';
import { navigate } from '../lib/router.js';
import { uid, useStored } from '../lib/store.js';
import Icon from '../components/Icon.jsx';
import JalaliDate from '../components/JalaliDate.jsx';
import './Welcome.css';

const ROLES = [
  { id: 'mother', label: 'مادر یا مادرِ آینده' },
  { id: 'father', label: 'پدر یا پدرِ آینده' },
  { id: 'single', label: 'هنوز ازدواج نکرده‌ام' }
];

/* شروع کار: سه سؤال کوتاه برای شخصی‌سازی؛ هر مرحله «بعداً» دارد */
export default function Welcome() {
  const id = useId();
  const [profile, setProfile] = useStored('profile', {});
  const [step, setStep] = useState(0);
  const [role, setRole] = useState(profile.role || '');
  const [stage, setStage] = useState(profile.stage || '');
  const [date, setDate] = useState(null);
  const [kid, setKid] = useState('');
  const [sex, setSex] = useState('');
  const needsKid = stage === 'baby' || stage === 'child';
  const steps = ['نقش', 'مرحله', needsKid ? 'بچه' : 'تاریخ'];

  const finish = () => {
    const next = { ...profile, onboarded: true };
    if (role) next.role = role;
    if (stage) next.stage = stage;
    if (stage === 'pregnancy' && date) next.lmp = toISO(date);
    if (needsKid && kid.trim()) {
      next.children = [...(profile.children || []), { id: uid(), name: kid.trim().slice(0, 20), birth: date ? toISO(date) : '', sex }];
    }
    setProfile(next);
    navigate('/me');
  };

  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap welcome">
        <div className="welcome__progress" role="progressbar" aria-valuemin={1} aria-valuemax={3} aria-valuenow={step + 1} aria-label={`مرحلهٔ ${step + 1} از ۳`}>
          {steps.map((s, i) => (
            <span key={s} className={i <= step ? 'is-on' : ''}>
              {s}
            </span>
          ))}
        </div>

        <section className="panel stack welcome__card" aria-labelledby={`${id}-h`}>
          {step === 0 && (
            <>
              <h1 id={`${id}-h`} className="welcome__q">
                خوش آمدید! شما کدام‌اید؟
              </h1>
              <div className="welcome__opts">
                {ROLES.map((r) => (
                  <label key={r.id} className="opt">
                    <input type="radio" name={`${id}-role`} checked={role === r.id} onChange={() => setRole(r.id)} />
                    <span>{r.label}</span>
                  </label>
                ))}
              </div>
            </>
          )}
          {step === 1 && (
            <>
              <h1 id={`${id}-h`} className="welcome__q">
                الان در کدام مرحله‌اید؟
              </h1>
              <div className="welcome__opts">
                {stages.map((s) => (
                  <label key={s.id} className="opt">
                    <input type="radio" name={`${id}-stage`} checked={stage === s.id} onChange={() => { setStage(s.id); setDate(null); }} />
                    <span>{s.label}</span>
                  </label>
                ))}
              </div>
            </>
          )}
          {step === 2 && stage === 'pregnancy' && (
            <>
              <h1 id={`${id}-h`} className="welcome__q">
                اولین روز آخرین پریودتان کی بود؟
              </h1>
              <p className="hint">با این تاریخ هفتهٔ بارداری و تاریخ تقریبی زایمان را حساب می‌کنیم.</p>
              <JalaliDate id={`${id}-d`} value={date} onChange={setDate} yearsBack={1} />
            </>
          )}
          {step === 2 && needsKid && (
            <>
              <h1 id={`${id}-h`} className="welcome__q">
                اسم و تاریخ تولد بچه (اختیاری)
              </h1>
              <div className="field">
                <label htmlFor={`${id}-k`}>اسم</label>
                <input id={`${id}-k`} className="input" maxLength={20} value={kid} onChange={(e) => setKid(e.target.value)} />
              </div>
              <div className="field">
                <span className="label">تاریخ تولد</span>
                <JalaliDate id={`${id}-b`} value={date} onChange={setDate} yearsBack={7} />
              </div>
              <fieldset className="field">
                <legend>جنسیت (برای نمودار رشد)</legend>
                <div className="chips">
                  {[
                    ['girl', 'دختر'],
                    ['boy', 'پسر']
                  ].map(([v, l]) => (
                    <label key={v} className="chip">
                      <input type="radio" name={`${id}-sex`} checked={sex === v} onChange={() => setSex(v)} />
                      {l}
                    </label>
                  ))}
                </div>
              </fieldset>
            </>
          )}
          {step === 2 && !needsKid && stage !== 'pregnancy' && (
            <>
              <h1 id={`${id}-h`} className="welcome__q">
                همه‌چیز آماده است
              </h1>
              <p className="hint">داشبوردتان بر اساس مرحلهٔ «{stages.find((s) => s.id === stage)?.label || 'عمومی'}» تنظیم می‌شود. هر وقت خواستید از پروفایل عوضش کنید.</p>
            </>
          )}

          <div className="welcome__nav">
            {step > 0 && (
              <button type="button" className="btn btn--line" onClick={() => setStep(step - 1)}>
                <Icon name="ChevronRight" size={18} />
                قبلی
              </button>
            )}
            <span className="welcome__spacer" />
            <button type="button" className="linkish" onClick={() => (step < 2 ? setStep(step + 1) : finish())}>
              بعداً
            </button>
            <button type="button" className="btn btn--star" onClick={() => (step < 2 ? setStep(step + 1) : finish())}>
              {step < 2 ? 'بعدی' : 'رفتن به داشبورد'}
              <Icon name="ChevronLeft" size={18} />
            </button>
          </div>
          <p className="hint">
            <Icon name="Lock" size={14} /> پاسخ‌ها فقط روی همین دستگاه ذخیره می‌شوند.
          </p>
        </section>
      </div>
    </main>
  );
}
