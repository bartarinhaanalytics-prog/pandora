import { useId, useState } from 'react';
import { formatJ, fromISO, toISO, today, diffDays } from '../lib/jalali.js';
import { faNum } from '../lib/fa.js';
import { href } from '../lib/router.js';
import { uid, useStored } from '../lib/store.js';
import Icon from '../components/Icon.jsx';
import JalaliDate from '../components/JalaliDate.jsx';
import PageHead from '../components/PageHead.jsx';
import { pregnancyFrom } from './Pregnancy.jsx';
import './Profile.css';

const ROLES = [
  { id: 'mother', label: 'مادر' },
  { id: 'father', label: 'پدر' },
  { id: 'single', label: 'قبل از ازدواج' }
];
const STAGES = [
  { id: 'before', label: 'آماده شدن برای ازدواج' },
  { id: 'trying', label: 'اقدام به بارداری' },
  { id: 'pregnancy', label: 'بارداری' },
  { id: 'baby', label: 'نوزاد (زیر یک سال)' },
  { id: 'child', label: 'کودک (۱ تا ۶ سال)' }
];

export function ageText(birth) {
  const days = diffDays(today(), birth);
  if (days < 0) return 'هنوز به دنیا نیامده';
  if (days < 31) return `${faNum(days)} روزه`;
  const months = Math.floor(days / 30.44);
  if (months < 24) return `${faNum(months)} ماهه`;
  return `${faNum(Math.floor(months / 12))} ساله`;
}

function ChildForm({ initial, onSave, onCancel }) {
  const id = useId();
  const [name, setName] = useState(initial?.name || '');
  const [birth, setBirth] = useState(initial?.birth ? fromISO(initial.birth) : null);
  const [sex, setSex] = useState(initial?.sex || '');
  const [err, setErr] = useState('');
  return (
    <form
      className="child-form"
      noValidate
      onSubmit={(e) => {
        e.preventDefault();
        if (!name.trim()) {
          setErr('اسم بچه را بنویسید.');
          return;
        }
        onSave({ id: initial?.id || uid(), name: name.trim().slice(0, 20), birth: birth ? toISO(birth) : '', sex });
      }}
    >
      <div className="field">
        <label htmlFor={`${id}-n`}>اسم</label>
        <input
          id={`${id}-n`}
          className="input"
          value={name}
          maxLength={20}
          onChange={(e) => {
            setName(e.target.value);
            setErr('');
          }}
          aria-invalid={Boolean(err)}
        />
        {err && (
          <p className="error" role="alert">
            <Icon name="CircleAlert" size={18} />
            {err}
          </p>
        )}
      </div>
      <div className="field">
        <span className="label" id={`${id}-b-label`}>
          تاریخ تولد (اختیاری)
        </span>
        <JalaliDate id={`${id}-b`} value={birth} onChange={setBirth} yearsBack={7} />
      </div>
      <fieldset className="field">
        <legend>جنسیت (برای نمودار رشد، اختیاری)</legend>
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
      <div className="actions">
        <button type="submit" className="btn btn--star">
          <Icon name="Check" size={18} />
          ذخیره
        </button>
        <button type="button" className="btn btn--line" onClick={onCancel}>
          انصراف
        </button>
      </div>
    </form>
  );
}

export default function Profile() {
  const id = useId();
  const [profile, setProfile] = useStored('profile', {});
  const [editing, setEditing] = useState(null); // null | 'new' | childId
  const [savedMsg, setSavedMsg] = useState(false);
  const kids = profile.children || [];
  const set = (patch) => {
    setProfile({ ...profile, ...patch });
    setSavedMsg(true);
  };
  const preg = profile.stage === 'pregnancy' ? pregnancyFrom(fromISO(profile.lmp)) : null;

  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title={profile.name ? `سلام ${profile.name}` : 'پروفایل و خانواده'}
          intro="بگویید در کدام مرحله‌اید و بچه‌هایتان را اضافه کنید تا مطالب، هفتهٔ بارداری و قصه‌ها برای خودتان تنظیم شود."
          crumbs={[{ label: 'من', path: '/me' }, { label: 'پروفایل' }]}
        />
        <p className="notice" style={{ maxWidth: 760, marginBottom: 'var(--s-6)' }}>
          <Icon name="Lock" size={18} />
          <span>تا راه‌اندازی ورود با موبایل، این اطلاعات فقط روی همین دستگاه ذخیره می‌شود و به جایی فرستاده نمی‌شود.</span>
        </p>
        <div className="two-col">
          <div className="stack">
            <section className="panel stack" aria-labelledby={`${id}-me`}>
              <h2 id={`${id}-me`} className="section-title">
                اطلاعات من
              </h2>
              <div className="field">
                <label htmlFor={`${id}-name`}>نام نمایشی</label>
                <input id={`${id}-name`} className="input" value={profile.name || ''} maxLength={24} onChange={(e) => set({ name: e.target.value })} />
              </div>
              <fieldset className="field">
                <legend>نقش</legend>
                <div className="chips">
                  {ROLES.map((r) => (
                    <label key={r.id} className="chip">
                      <input type="radio" name={`${id}-role`} checked={profile.role === r.id} onChange={() => set({ role: r.id })} />
                      {r.label}
                    </label>
                  ))}
                </div>
              </fieldset>
              <fieldset className="field">
                <legend>مرحلهٔ فعلی</legend>
                <div className="chips">
                  {STAGES.map((s) => (
                    <label key={s.id} className="chip">
                      <input type="radio" name={`${id}-stage`} checked={profile.stage === s.id} onChange={() => set({ stage: s.id })} />
                      {s.label}
                    </label>
                  ))}
                </div>
              </fieldset>
              {profile.stage === 'pregnancy' && (
                <div className="field">
                  <span className="label" id={`${id}-lmp-label`}>
                    اولین روز آخرین پریود
                  </span>
                  <JalaliDate id={`${id}-lmp`} value={fromISO(profile.lmp)} onChange={(d) => set({ lmp: d ? toISO(d) : '' })} yearsBack={1} />
                  {preg && (
                    <p className="success">
                      <Icon name="Baby" size={18} />
                      <span>
                        هفتهٔ {faNum(preg.week)} بارداری؛ زایمان حدود {formatJ(preg.due)}.{' '}
                        <a href={href(`/pregnancy/${preg.week}`)}>دیدن این هفته</a>
                      </span>
                    </p>
                  )}
                </div>
              )}
              {savedMsg && (
                <p className="hint" role="status">
                  تغییرات خودکار ذخیره شد.
                </p>
              )}
            </section>

            <section className="panel stack" aria-labelledby={`${id}-kids`}>
              <h2 id={`${id}-kids`} className="section-title">
                بچه‌ها
              </h2>
              {kids.length === 0 && editing !== 'new' && <p className="hint">هنوز بچه‌ای اضافه نکرده‌اید. اسمشان در قصه‌ساز آماده می‌شود.</p>}
              <ul className="rows kids">
                {kids.map((k) =>
                  editing === k.id ? (
                    <li key={k.id} className="row">
                      <ChildForm
                        initial={k}
                        onCancel={() => setEditing(null)}
                        onSave={(c) => {
                          set({ children: kids.map((x) => (x.id === k.id ? c : x)) });
                          setEditing(null);
                        }}
                      />
                    </li>
                  ) : (
                    <li key={k.id} className="row kid">
                      <span className="row__title">{k.name}</span>
                      <span className="row__meta">
                        {k.birth ? `${ageText(fromISO(k.birth))}، متولد ${formatJ(fromISO(k.birth))}` : 'تاریخ تولد ثبت نشده'}
                      </span>
                      <span className="kid__actions">
                        <button type="button" className="linkish" onClick={() => setEditing(k.id)}>
                          <Icon name="Pencil" size={16} />
                          ویرایش
                        </button>
                        <button
                          type="button"
                          className="linkish"
                          onClick={() => {
                            if (window.confirm(`${k.name} از فهرست حذف شود؟`)) set({ children: kids.filter((x) => x.id !== k.id) });
                          }}
                        >
                          <Icon name="Trash2" size={16} />
                          حذف
                        </button>
                      </span>
                    </li>
                  )
                )}
              </ul>
              {editing === 'new' ? (
                <ChildForm
                  onCancel={() => setEditing(null)}
                  onSave={(c) => {
                    set({ children: [...kids, c] });
                    setEditing(null);
                  }}
                />
              ) : (
                <button type="button" className="btn btn--line" onClick={() => setEditing('new')}>
                  <Icon name="Plus" size={18} />
                  افزودن بچه
                </button>
              )}
            </section>
          </div>

          <aside className="stack">
            <nav className="panel me-links" aria-label="بخش‌های من">
              <a href={href('/me/partner')}>
                <Icon name="HeartHandshake" size={22} />
                <span>
                  <strong>اتصال همسر</strong>
                  <small>همسرتان هفتهٔ بارداری، تقویم یا قصه‌ها را ببیند</small>
                </span>
              </a>
              <a href={href('/me/stories')}>
                <Icon name="BookHeart" size={22} />
                <span>
                  <strong>قصه‌های من</strong>
                  <small>قصه‌های ذخیره‌شده</small>
                </span>
              </a>
              <a href={href('/tools/cycle')}>
                <Icon name="CalendarDays" size={22} />
                <span>
                  <strong>تقویم قاعدگی و باروری</strong>
                  <small>پریود بعدی و روزهای باروری</small>
                </span>
              </a>
              <a href={href('/privacy')}>
                <Icon name="Lock" size={22} />
                <span>
                  <strong>حریم خصوصی و حذف داده‌ها</strong>
                  <small>همه‌چیز را با یک دکمه پاک کنید</small>
                </span>
              </a>
            </nav>
          </aside>
        </div>
      </div>
    </main>
  );
}
