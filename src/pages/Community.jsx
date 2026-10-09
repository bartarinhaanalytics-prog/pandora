import { useId, useState } from 'react';
import { formatJ, fromISO, toISO, today } from '../lib/jalali.js';
import { href } from '../lib/router.js';
import { uid, useStored } from '../lib/store.js';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';
import './Community.css';

export const groups = [
  { id: 'pregnant', label: 'مادرها و پدرهای باردار', note: 'بر اساس ماه زایمان' },
  { id: 'newborn', label: 'نوزاد، ۰ تا ۱۲ ماه', note: 'شب‌بیداری، شیردهی، واکسن' },
  { id: 'toddler', label: 'نوپا، ۱ تا ۳ سال', note: 'خواب، غذا، حرف زدن' },
  { id: 'preschool', label: 'کودک، ۳ تا ۶ سال', note: 'مهدکودک، رفتار، بازی' },
  { id: 'trying', label: 'اقدام به بارداری', note: 'انتظار، آزمایش‌ها، امید' },
  { id: 'dads', label: 'پدرها', note: 'گفتگوی مردانه دربارهٔ پدری' }
];

const RULES = [
  'نام کامل، شماره، نشانی یا عکس بچه‌ها را منتشر نکنید.',
  'توصیهٔ دارویی ندهید؛ تجربه بگویید، نه نسخه.',
  'تبلیغ و فروش ممنوع است.',
  'با احترام بنویسید؛ هر خانواده راه خودش را دارد.'
];

export default function Community({ groupId }) {
  const id = useId();
  const group = groups.find((g) => g.id === groupId);
  const [drafts, setDrafts] = useStored('communityDrafts', []);
  const [form, setForm] = useState({ nick: '', title: '', text: '' });
  const [err, setErr] = useState('');
  const mine = drafts.filter((d) => d.group === groupId);

  const save = (e) => {
    e.preventDefault();
    if (form.title.trim().length < 5 || form.text.trim().length < 15) {
      setErr('عنوان (دست‌کم ۵ حرف) و متن (دست‌کم ۱۵ حرف) را بنویسید.');
      return;
    }
    setErr('');
    setDrafts([{ id: uid(), group: groupId, ...form, date: toISO(today()) }, ...drafts]);
    setForm({ nick: form.nick, title: '', text: '' });
  };

  if (!group) {
    return (
      <main id="main" className="page" tabIndex={-1}>
        <div className="wrap">
          <PageHead
            title="جامعهٔ والدین"
            intro="گفتگو با پدر و مادرهایی که بچه‌شان هم‌سن بچهٔ شماست؛ با نام نمایشی، بدون قضاوت."
            crumbs={[{ label: 'جامعه' }]}
          />
          <p className="notice" style={{ maxWidth: 760, marginBottom: 'var(--s-6)' }}>
            <Icon name="Info" size={18} />
            <span>جامعه به‌زودی باز می‌شود. فعلاً می‌توانید گروهتان را ببینید و اولین گفتگو را به‌صورت پیش‌نویس آماده کنید.</span>
          </p>
          <div className="two-col">
            <ul className="groups">
              {groups.map((g) => (
                <li key={g.id}>
                  <a href={href(`/community/${g.id}`)} className="group">
                    <Icon name="Users" size={22} />
                    <span>
                      <strong>{g.label}</strong>
                      <small>{g.note}</small>
                    </span>
                    <Icon name="ChevronLeft" size={20} className="group__go" />
                  </a>
                </li>
              ))}
            </ul>
            <aside className="panel stack">
              <h2 className="section-title">قانون‌های جامعه</h2>
              <ul className="promises">
                {RULES.map((r) => (
                  <li key={r}>
                    <Icon name="ShieldCheck" size={18} />
                    <span>{r}</span>
                  </li>
                ))}
              </ul>
              <p className="hint">
                <Icon name="Flag" size={16} /> هر پیام دکمهٔ گزارش دارد و تیم دردونه گزارش‌ها را بررسی می‌کند.
              </p>
            </aside>
          </div>
        </div>
      </main>
    );
  }

  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead title={group.label} intro={group.note} crumbs={[{ label: 'جامعه', path: '/community' }, { label: group.label }]} />
        <div className="two-col">
          <section aria-labelledby={`${id}-t`} className="stack">
            <h2 id={`${id}-t`} className="section-title">
              گفتگوها
            </h2>
            <div className="empty">
              <p>هنوز گفتگویی در این گروه منتشر نشده. جامعه به‌زودی باز می‌شود؛ اولین گفتگو را از همین حالا آماده کنید.</p>
            </div>
            {mine.length > 0 && (
              <ul className="rows" style={{ listStyle: 'none', padding: 0, margin: 0 }}>
                {mine.map((d) => (
                  <li key={d.id} className="row">
                    <span className="row__title">{d.title}</span>
                    <p>{d.text}</p>
                    <span className="row__meta">
                      {d.nick || 'بی‌نام'} · {formatJ(fromISO(d.date))}
                      <span className="tag">پیش‌نویس؛ هنوز منتشر نشده</span>
                      <button type="button" className="linkish" onClick={() => setDrafts(drafts.filter((x) => x.id !== d.id))}>
                        <Icon name="Trash2" size={16} />
                        حذف
                      </button>
                    </span>
                  </li>
                ))}
              </ul>
            )}
          </section>
          <form className="panel stack" onSubmit={save} noValidate aria-labelledby={`${id}-f`}>
            <h2 id={`${id}-f`} className="section-title">
              گفتگوی تازه
            </h2>
            <div className="field">
              <label htmlFor={`${id}-n`}>نام نمایشی</label>
              <input id={`${id}-n`} className="input" maxLength={20} placeholder="مثلاً مامانِ آوا" value={form.nick} onChange={(e) => setForm({ ...form, nick: e.target.value })} />
            </div>
            <div className="field">
              <label htmlFor={`${id}-ti`}>عنوان</label>
              <input id={`${id}-ti`} className="input" maxLength={90} value={form.title} onChange={(e) => setForm({ ...form, title: e.target.value })} />
            </div>
            <div className="field">
              <label htmlFor={`${id}-x`}>متن</label>
              <textarea id={`${id}-x`} className="textarea" maxLength={2000} value={form.text} onChange={(e) => setForm({ ...form, text: e.target.value })} aria-invalid={Boolean(err)} />
            </div>
            {err && (
              <p className="error" role="alert">
                <Icon name="CircleAlert" size={18} />
                {err}
              </p>
            )}
            <button type="submit" className="btn btn--star">
              <Icon name="Pencil" size={18} />
              ذخیرهٔ پیش‌نویس
            </button>
            <p className="hint">پیش‌نویس فقط روی همین دستگاه است و بعد از باز شدن جامعه می‌توانید منتشرش کنید.</p>
          </form>
        </div>
      </div>
    </main>
  );
}
