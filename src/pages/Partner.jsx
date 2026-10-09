import { useId, useState } from 'react';
import { post } from '../lib/api.js';
import { formatJ, fromISO, toISO, today } from '../lib/jalali.js';
import { normalizePhone, phoneError, faNum } from '../lib/fa.js';
import { useStored } from '../lib/store.js';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';

const SHARES = [
  { id: 'pregnancy', label: 'هفتهٔ بارداری و نکته‌های پدر', on: true },
  { id: 'stories', label: 'قصه‌های ذخیره‌شده', on: true },
  { id: 'children', label: 'اطلاعات بچه‌ها', on: true },
  { id: 'cycle', label: 'تقویم قاعدگی و باروری', on: false }
];

export default function Partner() {
  const id = useId();
  const [invite, setInvite] = useStored('partner', null);
  const [phone, setPhone] = useState('');
  const [shares, setShares] = useState(() => Object.fromEntries(SHARES.map((s) => [s.id, s.on])));
  const [err, setErr] = useState('');
  const [status, setStatus] = useState('idle');
  const [copied, setCopied] = useState(false);

  const send = async (e) => {
    e.preventDefault();
    const m = phoneError(phone);
    setErr(m);
    if (m) return;
    setStatus('loading');
    try {
      const r = await post('/partner/invite', { phone: normalizePhone(phone), shares });
      setInvite({ phone: normalizePhone(phone), shares, date: toISO(today()), local: Boolean(r.local) });
      setStatus('idle');
    } catch {
      setStatus('failed');
    }
  };

  const message = 'سلام! من از «دردونه» استفاده می‌کنم؛ همراه رایگان خانواده برای بارداری، بچه‌داری و قصهٔ شب. تو هم بیا تا با هم دنبالش کنیم: ' + window.location.origin + window.location.pathname;
  const copy = async () => {
    try {
      if (navigator.share) await navigator.share({ text: message });
      else {
        await navigator.clipboard.writeText(message);
        setCopied(true);
      }
    } catch {
      /* لغو شد */
    }
  };

  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title="اتصال همسر"
          intro="همسرتان را دعوت کنید تا هفته‌های بارداری، قصه‌ها و اطلاعات بچه‌ها را با هم دنبال کنید. خودتان انتخاب می‌کنید چه چیزی را ببیند."
          crumbs={[{ label: 'پروفایل', path: '/me' }, { label: 'اتصال همسر' }]}
        />
        <div className="two-col">
          {invite ? (
            <section className="panel stack" aria-labelledby={`${id}-s`}>
              <h2 id={`${id}-s`} className="section-title">
                دعوت ثبت شد
              </h2>
              <p className="row__meta">
                <span className="ltr num">{faNum(invite.phone)}</span>، {formatJ(fromISO(invite.date))}
              </p>
              <ul className="promises">
                {SHARES.map((s) => (
                  <li key={s.id}>
                    <Icon name={invite.shares[s.id] ? 'Check' : 'X'} size={18} />
                    <span>
                      {s.label}: {invite.shares[s.id] ? 'می‌بیند' : 'نمی‌بیند'}
                    </span>
                  </li>
                ))}
              </ul>
              {invite.local && (
                <p className="notice">
                  <Icon name="Info" size={18} />
                  <span>سرور دردونه هنوز راه‌اندازی نشده، پس پیامکی فرستاده نشد. تا آن موقع می‌توانید پیام دعوت را خودتان بفرستید.</span>
                </p>
              )}
              <div className="actions">
                <button type="button" className="btn btn--star" onClick={copy}>
                  <Icon name="Share2" size={18} />
                  فرستادن پیام دعوت
                </button>
                <button type="button" className="btn btn--line" onClick={() => setInvite(null)}>
                  <Icon name="X" size={18} />
                  لغو دعوت و قطع اتصال
                </button>
              </div>
              {copied && (
                <p className="success" role="status">
                  <Icon name="Check" size={18} />
                  پیام دعوت کپی شد.
                </p>
              )}
            </section>
          ) : (
            <form className="panel stack" onSubmit={send} noValidate aria-labelledby={`${id}-f`}>
              <h2 id={`${id}-f`} className="section-title">
                دعوت همسر
              </h2>
              <div className="field">
                <label htmlFor={`${id}-p`}>شمارهٔ موبایل همسر</label>
                <input
                  id={`${id}-p`}
                  className="input"
                  type="tel"
                  inputMode="tel"
                  dir="ltr"
                  placeholder="۰۹۱۲ ۳۴۵ ۶۷۸۹"
                  value={phone}
                  onChange={(e) => {
                    setPhone(e.target.value);
                    setErr('');
                  }}
                  aria-invalid={Boolean(err)}
                  aria-describedby={err ? `${id}-pe` : undefined}
                  style={{ textAlign: 'right' }}
                />
                {err && (
                  <p id={`${id}-pe`} className="error" role="alert">
                    <Icon name="CircleAlert" size={18} />
                    {err}
                  </p>
                )}
              </div>
              <fieldset className="field">
                <legend>همسرم این‌ها را ببیند</legend>
                <div className="checks">
                  {SHARES.map((s) => (
                    <label key={s.id} className="check">
                      <input type="checkbox" checked={shares[s.id]} onChange={(e) => setShares({ ...shares, [s.id]: e.target.checked })} />
                      <span>{s.label}</span>
                    </label>
                  ))}
                </div>
                <span className="hint">تقویم قاعدگی پیش‌فرض خاموش است؛ فقط اگر خودتان بخواهید روشنش کنید.</span>
              </fieldset>
              <div className="actions">
                <button type="submit" className="btn btn--star" disabled={status === 'loading'}>
                  {status === 'loading' ? <Icon name="Loader2" size={18} className="spin" /> : <Icon name="UserPlus" size={18} />}
                  فرستادن دعوت
                </button>
              </div>
              {status === 'failed' && (
                <p className="error" role="alert">
                  <Icon name="CircleAlert" size={18} />
                  فرستادن دعوت انجام نشد. اتصال اینترنت را بررسی کنید و دوباره امتحان کنید.
                </p>
              )}
            </form>
          )}
          <aside className="stack prose">
            <section>
              <h2 className="section-title">چرا با همسر؟</h2>
              <p>بارداری و بچه‌داری کار دو نفر است. وقتی پدر بداند جنین این هفته چقدر بزرگ شده یا امشب کدام قصه خوانده شده، راحت‌تر همراهی می‌کند.</p>
              <p>هر وقت بخواهید می‌توانید اتصال را قطع کنید یا چیزهایی را که همسرتان می‌بیند تغییر دهید.</p>
            </section>
          </aside>
        </div>
      </div>
    </main>
  );
}
