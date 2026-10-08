import { useId, useState } from 'react';
import { finalCta } from '../data/content.js';
import Icon from './Icon.jsx';
import './FinalCta.css';

const FA_DIGITS = '۰۱۲۳۴۵۶۷۸۹';
const AR_DIGITS = '٠١٢٣٤٥٦٧٨٩';

function normalizePhone(value) {
  return value
    .replace(/[۰-۹]/g, (d) => String(FA_DIGITS.indexOf(d)))
    .replace(/[٠-٩]/g, (d) => String(AR_DIGITS.indexOf(d)))
    .replace(/[\s\-()]/g, '')
    .replace(/^\+98/, '0')
    .replace(/^0098/, '0');
}

const toFaDigits = (s) => s.replace(/\d/g, (d) => FA_DIGITS[d]);

function validate(raw) {
  const v = normalizePhone(raw);
  if (!v) return 'شمارهٔ موبایل را وارد کنید.';
  if (!/^09\d{9}$/.test(v)) return 'شماره باید ۱۱ رقم باشد و با ۰۹ شروع شود؛ مثل ۰۹۱۲۳۴۵۶۷۸۹.';
  return '';
}

/** ارسال به سرور؛ تا وقتی آدرس API تنظیم نشده، موفقیت شبیه‌سازی می‌شود. */
async function requestCode(phone) {
  const url = import.meta.env.VITE_SIGNUP_URL;
  if (!url) {
    await new Promise((r) => setTimeout(r, 900));
    return;
  }
  const res = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ phone })
  });
  if (!res.ok) throw new Error(String(res.status));
}

export default function FinalCta() {
  const id = useId();
  const [phone, setPhone] = useState('');
  const [error, setError] = useState('');
  const [status, setStatus] = useState('idle'); // idle | loading | success | failed

  const onSubmit = async (e) => {
    e.preventDefault();
    const msg = validate(phone);
    setError(msg);
    if (msg) return;
    setStatus('loading');
    try {
      await requestCode(normalizePhone(phone));
      setStatus('success');
    } catch {
      setStatus('failed');
    }
  };

  return (
    <section id="start" className="section final on-night" aria-labelledby="final-title">
      <div className="container final__grid">
        <div className="final__pearl" aria-hidden="true">
          <span className="final__arch" />
          <span className="final__cradle" />
          <span className="final__orb" />
        </div>

        <div className="final__copy">
          <h2 id="final-title" className="h2">
            {finalCta.title}
          </h2>
          <p className="lead">{finalCta.text}</p>

          {status === 'success' ? (
            <div className="final__result final__result--ok" role="status">
              <Icon name="Check" size={22} />
              <div>
                <p className="final__result-title">کد تأیید فرستاده شد</p>
                <p>کد ۵ رقمی به شمارهٔ <span className="ltr num">{toFaDigits(normalizePhone(phone))}</span> پیامک شد.</p>
              </div>
            </div>
          ) : (
            <form className="final__form" onSubmit={onSubmit} noValidate>
              <label htmlFor={`${id}-phone`} className="final__label">
                شمارهٔ موبایل
              </label>
              <div className="final__row">
                <input
                  id={`${id}-phone`}
                  name="phone"
                  type="tel"
                  inputMode="tel"
                  autoComplete="tel"
                  dir="ltr"
                  placeholder="۰۹۱۲ ۳۴۵ ۶۷۸۹"
                  className="final__input"
                  value={phone}
                  aria-invalid={Boolean(error)}
                  aria-describedby={`${id}-help${error ? ` ${id}-err` : ''}`}
                  onChange={(e) => {
                    setPhone(e.target.value);
                    if (error) setError('');
                    if (status === 'failed') setStatus('idle');
                  }}
                />
                <button type="submit" className="btn btn--accent final__submit" disabled={status === 'loading'}>
                  {status === 'loading' ? (
                    <>
                      <Icon name="Loader2" size={18} className="spin" />
                      در حال فرستادن…
                    </>
                  ) : (
                    finalCta.button
                  )}
                </button>
              </div>
              <p id={`${id}-help`} className="final__help">
                کد تأیید پیامک می‌شود. شماره‌تان را به کسی نمی‌دهیم.
              </p>
              {error && (
                <p id={`${id}-err`} className="final__error" role="alert">
                  <Icon name="CircleAlert" size={18} />
                  {error}
                </p>
              )}
              {status === 'failed' && (
                <p className="final__error" role="alert">
                  <Icon name="CircleAlert" size={18} />
                  فرستادن کد انجام نشد. اتصال اینترنت را بررسی کنید و دوباره امتحان کنید.
                </p>
              )}
            </form>
          )}
        </div>
      </div>
    </section>
  );
}
