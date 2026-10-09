import { useId, useMemo, useState } from 'react';
import { qa, qaStages } from '../data/qa.js';
import { post } from '../lib/api.js';
import { formatJ, fromISO, toISO, today } from '../lib/jalali.js';
import { normalize } from '../lib/fa.js';
import { href } from '../lib/router.js';
import { uid, useStored } from '../lib/store.js';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';

/* سؤال‌های پاسخ‌داده‌شدهٔ مشابه، بر اساس واژه‌های مشترک */
function similar(text) {
  const words = normalize(text)
    .split(' ')
    .filter((w) => w.length > 2);
  if (words.length < 2) return [];
  return qa
    .map((x) => {
      const hay = normalize(`${x.q} ${x.a}`);
      return { x, score: words.filter((w) => hay.includes(w)).length };
    })
    .filter((r) => r.score >= 2)
    .sort((a, b) => b.score - a.score)
    .slice(0, 3)
    .map((r) => r.x);
}

export default function Ask() {
  const id = useId();
  const [text, setText] = useState('');
  const [stage, setStage] = useState(qaStages[0].id);
  const [err, setErr] = useState('');
  const [status, setStatus] = useState('idle');
  const [mine, setMine] = useStored('questions', []);
  const matches = useMemo(() => similar(text), [text]);

  const submit = async (e) => {
    e.preventDefault();
    if (text.trim().length < 15) {
      setErr('سؤال را کمی کامل‌تر بنویسید تا متخصص بتواند دقیق جواب دهد (دست‌کم ۱۵ حرف).');
      return;
    }
    setErr('');
    setStatus('loading');
    try {
      const r = await post('/questions', { text: text.trim(), stage });
      setMine([{ id: uid(), text: text.trim(), stage, date: toISO(today()), sent: !r.local }, ...mine]);
      setText('');
      setStatus(r.local ? 'local' : 'sent');
    } catch {
      setStatus('failed');
    }
  };

  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title="پرسش بی‌نام از متخصص"
          intro="سؤالی که روی‌تان نمی‌شود از کسی بپرسید، این‌جا بی‌نام بپرسید. نام و شماره‌تان به متخصص نمی‌رسد."
          crumbs={[{ label: 'سلامت', path: '/health' }, { label: 'پرسش بی‌نام' }]}
        />
        <div className="two-col">
          <form className="panel stack" onSubmit={submit} noValidate>
            <div className="field">
              <label htmlFor={`${id}-s`}>دربارهٔ چه مرحله‌ای است؟</label>
              <select id={`${id}-s`} className="select" value={stage} onChange={(e) => setStage(e.target.value)}>
                {qaStages.map((s) => (
                  <option key={s.id} value={s.id}>
                    {s.label}
                  </option>
                ))}
              </select>
            </div>
            <div className="field">
              <label htmlFor={`${id}-q`}>سؤال شما</label>
              <textarea
                id={`${id}-q`}
                className="textarea"
                value={text}
                maxLength={1200}
                onChange={(e) => {
                  setText(e.target.value);
                  setErr('');
                  if (status !== 'loading') setStatus('idle');
                }}
                aria-invalid={Boolean(err)}
                aria-describedby={`${id}-h${err ? ` ${id}-e` : ''}`}
              />
              <span id={`${id}-h`} className="hint">
                نام، شماره، نشانی یا عکس ننویسید. سن و مدت مشکل را اگر بنویسید، جواب دقیق‌تر می‌شود.
              </span>
              {err && (
                <p id={`${id}-e`} className="error" role="alert">
                  <Icon name="CircleAlert" size={18} />
                  {err}
                </p>
              )}
            </div>
            {matches.length > 0 && (
              <div className="similar" aria-live="polite">
                <p className="label">شاید جوابتان این‌جا باشد:</p>
                <ul>
                  {matches.map((m) => (
                    <li key={m.id}>
                      <a href={href('/qa', { stage: m.stage, q: m.id })}>{m.q}</a>
                    </li>
                  ))}
                </ul>
              </div>
            )}
            <div className="actions">
              <button type="submit" className="btn btn--star" disabled={status === 'loading'}>
                {status === 'loading' ? <Icon name="Loader2" size={18} className="spin" /> : <Icon name="Lock" size={18} />}
                فرستادن بی‌نام
              </button>
            </div>
            {status === 'sent' && (
              <p className="success" role="status">
                <Icon name="Check" size={18} />
                سؤال رسید. وقتی متخصص جواب دهد، در همین صفحه می‌بینید.
              </p>
            )}
            {status === 'local' && (
              <p className="notice" role="status">
                <Icon name="Info" size={18} />
                <span>
                  پاسخ‌دهی متخصصان هنوز راه‌اندازی نشده، پس سؤال فرستاده نشد و فقط روی همین دستگاه نگه داشته شد. اگر فوری است، با پزشک تماس
                  بگیرید.
                </span>
              </p>
            )}
            {status === 'failed' && (
              <p className="error" role="alert">
                <Icon name="CircleAlert" size={18} />
                فرستادن انجام نشد. اتصال اینترنت را بررسی کنید و دوباره امتحان کنید.
              </p>
            )}
          </form>
          <aside className="stack">
            <section className="panel stack">
              <h2 className="section-title">بی‌نام یعنی چه؟</h2>
              <ul className="promises">
                <li>
                  <Icon name="Lock" size={18} />
                  <span>متخصص فقط متن سؤال و مرحله را می‌بیند؛ نه نام، نه شماره.</span>
                </li>
                <li>
                  <Icon name="Users" size={18} />
                  <span>اگر جواب برای دیگران هم مفید باشد، بدون هیچ نشانه‌ای از شما در «پرسش‌های سلامت» منتشر می‌شود.</span>
                </li>
                <li>
                  <Icon name="Phone" size={18} />
                  <span>
                    در وضعیت اورژانسی منتظر جواب نمانید:{' '}
                    <a href="tel:115" className="num">
                      ۱۱۵
                    </a>
                  </span>
                </li>
              </ul>
            </section>
            {mine.length > 0 && (
              <section className="panel" aria-labelledby={`${id}-m`}>
                <h2 id={`${id}-m`} className="section-title">
                  سؤال‌های من
                </h2>
                <ul className="rows" style={{ listStyle: 'none', padding: 0, margin: 0 }}>
                  {mine.map((m) => (
                    <li key={m.id} className="row">
                      <p>{m.text}</p>
                      <span className="row__meta">
                        {formatJ(fromISO(m.date))}
                        <span className="tag">{m.sent ? 'در انتظار پاسخ' : 'فرستاده نشده'}</span>
                        <button type="button" className="linkish" onClick={() => setMine(mine.filter((x) => x.id !== m.id))}>
                          <Icon name="Trash2" size={16} />
                          حذف
                        </button>
                      </span>
                    </li>
                  ))}
                </ul>
              </section>
            )}
          </aside>
        </div>
      </div>
    </main>
  );
}
