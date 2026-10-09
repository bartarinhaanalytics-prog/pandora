import { useId, useState } from 'react';
import { post } from '../lib/api.js';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';

const topics = ['پیشنهاد', 'گزارش مشکل', 'گزارش محتوای نادرست یا نامناسب', 'همکاری پزشکان', 'سایر'];

export default function Contact() {
  const id = useId();
  const [form, setForm] = useState({ topic: topics[0], reply: '', message: '' });
  const [errors, setErrors] = useState({});
  const [status, setStatus] = useState('idle');
  const set = (k) => (e) => setForm((f) => ({ ...f, [k]: e.target.value }));

  const submit = async (e) => {
    e.preventDefault();
    const err = {};
    if (form.message.trim().length < 10) err.message = 'پیام را کمی کامل‌تر بنویسید (دست‌کم ۱۰ حرف).';
    setErrors(err);
    if (Object.keys(err).length) return;
    setStatus('loading');
    try {
      const r = await post('/contact', form);
      setStatus(r.local ? 'local' : 'sent');
    } catch {
      setStatus('failed');
    }
  };

  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead title="تماس با ما" intro="پیشنهاد، گزارش مشکل یا پیام برای همکاری؛ همه را می‌خوانیم." crumbs={[{ label: 'تماس با ما' }]} />
        <div className="two-col">
          <form className="panel stack" onSubmit={submit} noValidate>
            <div className="field">
              <label htmlFor={`${id}-t`}>موضوع</label>
              <select id={`${id}-t`} className="select" value={form.topic} onChange={set('topic')}>
                {topics.map((t) => (
                  <option key={t}>{t}</option>
                ))}
              </select>
            </div>
            <div className="field">
              <label htmlFor={`${id}-r`}>ایمیل یا شماره برای پاسخ (اختیاری)</label>
              <input id={`${id}-r`} className="input" dir="ltr" value={form.reply} onChange={set('reply')} autoComplete="email" />
            </div>
            <div className="field">
              <label htmlFor={`${id}-m`}>پیام</label>
              <textarea
                id={`${id}-m`}
                className="textarea"
                value={form.message}
                onChange={set('message')}
                aria-invalid={Boolean(errors.message)}
                aria-describedby={errors.message ? `${id}-me` : undefined}
              />
              {errors.message && (
                <p id={`${id}-me`} className="error" role="alert">
                  <Icon name="CircleAlert" size={18} />
                  {errors.message}
                </p>
              )}
            </div>
            <div className="actions">
              <button type="submit" className="btn btn--star" disabled={status === 'loading'}>
                {status === 'loading' ? <Icon name="Loader2" size={18} className="spin" /> : <Icon name="Send" size={18} />}
                فرستادن پیام
              </button>
            </div>
            {status === 'sent' && (
              <p className="success" role="status">
                <Icon name="Check" size={18} />
                پیام رسید. ممنون که نوشتید.
              </p>
            )}
            {status === 'local' && (
              <p className="notice" role="status">
                <Icon name="Info" size={18} />
                <span>سرور پیام‌ها هنوز راه‌اندازی نشده، پس این پیام فرستاده نشد. لطفاً بعد از راه‌اندازی دوباره بفرستید.</span>
              </p>
            )}
            {status === 'failed' && (
              <p className="error" role="alert">
                <Icon name="CircleAlert" size={18} />
                فرستادن پیام انجام نشد. اتصال اینترنت را بررسی کنید و دوباره امتحان کنید.
              </p>
            )}
          </form>
          <aside className="stack prose">
            <section>
              <h2 className="section-title">قبل از نوشتن</h2>
              <p>اگر سؤال پزشکی دارید، از بخش «پرسش بی‌نام از متخصص» بپرسید تا به دست متخصص برسد.</p>
              <p>
                در وضعیت اورژانسی منتظر پاسخ نمانید و با{' '}
                <a href="tel:115" className="num">
                  ۱۱۵
                </a>{' '}
                تماس بگیرید.
              </p>
            </section>
          </aside>
        </div>
      </div>
    </main>
  );
}
