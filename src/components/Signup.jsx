import { useId, useState } from 'react';
import BackdropVideo from './BackdropVideo.jsx';
import Icon from './Icon.jsx';
import './Signup.css';

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

export default function Signup() {
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
    <section id="start" className="signup" aria-labelledby="signup-title">
      <BackdropVideo name="wide" position="40% 60%" />
      <div className="signup__scrim" aria-hidden="true" />
      <div className="wrap signup__inner">
        <div className="signup__panel quilt">
          <h2 id="signup-title">قصه‌های امشب را نگه دارید</h2>
          <p className="signup__lead">
            با شمارهٔ موبایل ثبت‌نام کنید تا قصه‌ها ذخیره شوند، هر شب قصهٔ تازه بسازید و پرسش‌های سلامت هر مرحله را دنبال کنید. رایگان
            است و رایگان می‌ماند.
          </p>

          {status === 'success' ? (
            <div className="signup__ok" role="status">
              <Icon name="Check" size={22} />
              <div>
                <p className="signup__ok-title">کد تأیید فرستاده شد</p>
                <p>
                  کد ۵ رقمی به شمارهٔ <span className="ltr num">{toFaDigits(normalizePhone(phone))}</span> پیامک شد.
                </p>
              </div>
            </div>
          ) : (
            <form className="signup__form" onSubmit={onSubmit} noValidate>
              <label htmlFor={`${id}-phone`}>شمارهٔ موبایل</label>
              <div className="signup__row">
                <input
                  id={`${id}-phone`}
                  name="phone"
                  type="tel"
                  inputMode="tel"
                  autoComplete="tel"
                  dir="ltr"
                  placeholder="۰۹۱۲ ۳۴۵ ۶۷۸۹"
                  className="signup__input"
                  value={phone}
                  aria-invalid={Boolean(error)}
                  aria-describedby={`${id}-help${error ? ` ${id}-err` : ''}`}
                  onChange={(e) => {
                    setPhone(e.target.value);
                    if (error) setError('');
                    if (status === 'failed') setStatus('idle');
                  }}
                />
                <button type="submit" className="btn btn--saffron" disabled={status === 'loading'}>
                  {status === 'loading' ? (
                    <>
                      <Icon name="Loader2" size={18} className="spin" />
                      در حال فرستادن…
                    </>
                  ) : (
                    'فرستادن کد'
                  )}
                </button>
              </div>
              <p id={`${id}-help`} className="signup__help">
                کد تأیید پیامک می‌شود. شماره‌تان را به کسی نمی‌دهیم.
              </p>
              {error && (
                <p id={`${id}-err`} className="signup__error" role="alert">
                  <Icon name="CircleAlert" size={18} />
                  {error}
                </p>
              )}
              {status === 'failed' && (
                <p className="signup__error" role="alert">
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
