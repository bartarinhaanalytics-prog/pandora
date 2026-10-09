import { href } from '../lib/router.js';
import { useStored } from '../lib/store.js';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';

const NOTIFY = [
  { id: 'daily', label: 'پیام روزانه' },
  { id: 'vaccine', label: 'یادآور نوبت واکسن' },
  { id: 'sleep', label: 'یادآور ساعت خواب بچه' },
  { id: 'period', label: 'یادآور پریود بعدی' }
];

export default function Settings() {
  const [s, setS] = useStored('settings', {});
  const set = (patch) => setS({ ...s, ...patch });
  const notify = s.notify || {};
  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead title="تنظیمات" crumbs={[{ label: 'من', path: '/me' }, { label: 'تنظیمات' }]} />
        <div className="narrow stack">
          <section className="panel stack" aria-labelledby="set-read">
            <h2 id="set-read" className="section-title">
              خواندن قصه در شب
            </h2>
            <label className="check">
              <input type="checkbox" checked={Boolean(s.dimReading)} onChange={(e) => set({ dimReading: e.target.checked })} />
              <span>همیشه با «نور کم» باز شود (متن گرم و کم‌نور برای اتاق تاریک)</span>
            </label>
            <div className="field">
              <label htmlFor="set-size">اندازهٔ متن قصه</label>
              <input
                id="set-size"
                type="range"
                className="range"
                min="1"
                max="2"
                step="0.125"
                value={s.storySize || 1.25}
                onChange={(e) => set({ storySize: Number(e.target.value) })}
              />
              <p style={{ fontSize: `${s.storySize || 1.25}rem`, lineHeight: 2 }}>یکی بود، یکی نبود…</p>
            </div>
          </section>

          <section className="panel stack" aria-labelledby="set-dash">
            <h2 id="set-dash" className="section-title">
              داشبورد
            </h2>
            <label className="check">
              <input type="checkbox" checked={s.dailyTip !== false} onChange={(e) => set({ dailyTip: e.target.checked })} />
              <span>نمایش پیام روزانه</span>
            </label>
          </section>

          <section className="panel stack" aria-labelledby="set-notify">
            <h2 id="set-notify" className="section-title">
              اعلان‌ها
            </h2>
            <p className="notice">
              <Icon name="Info" size={18} />
              <span>اعلان‌ها بعد از راه‌اندازی اپ و سرور فرستاده می‌شوند؛ انتخاب‌هایتان از حالا ذخیره می‌ماند.</span>
            </p>
            {NOTIFY.map((n) => (
              <label key={n.id} className="check">
                <input type="checkbox" checked={Boolean(notify[n.id])} onChange={(e) => set({ notify: { ...notify, [n.id]: e.target.checked } })} />
                <span>{n.label}</span>
              </label>
            ))}
          </section>

          <section className="panel stack" aria-labelledby="set-acc">
            <h2 id="set-acc" className="section-title">
              حساب و حریم خصوصی
            </h2>
            <nav className="me-links" style={{ padding: 0 }} aria-labelledby="set-acc">
              <a href={href('/me/profile')}>
                <Icon name="User" size={20} />
                <span>
                  <strong>پروفایل و خانواده</strong>
                </span>
              </a>
              <a href={href('/me/partner')}>
                <Icon name="HeartHandshake" size={20} />
                <span>
                  <strong>اتصال همسر</strong>
                </span>
              </a>
              <a href={href('/privacy')}>
                <Icon name="Trash2" size={20} />
                <span>
                  <strong>حذف همهٔ داده‌ها</strong>
                  <small>در صفحهٔ حریم خصوصی</small>
                </span>
              </a>
            </nav>
            <p className="hint">ورود و خروج با شمارهٔ موبایل بعد از راه‌اندازی سرور اضافه می‌شود.</p>
          </section>
        </div>
      </div>
    </main>
  );
}
