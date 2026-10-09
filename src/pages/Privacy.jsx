import { useState } from 'react';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';

const sections = [
  {
    t: 'الان چه چیزی کجا ذخیره می‌شود',
    p: [
      'در این نسخه هنوز سروری در کار نیست. هر چیزی که در پروفایل، تقویم قاعدگی، قصه‌ها، پرسش‌ها یا جامعه می‌نویسید فقط در حافظهٔ مرورگر همین دستگاه می‌ماند و به هیچ‌جا فرستاده نمی‌شود.',
      'اگر مرورگر را پاک کنید یا از دستگاه دیگری وارد شوید، این داده‌ها در دسترس نیستند.'
    ]
  },
  {
    t: 'بعد از راه‌اندازی چه چیزی نگه می‌داریم',
    p: [
      'شمارهٔ موبایل برای ورود، اطلاعات پروفایل و بچه‌ها، داده‌های تقویم، قصه‌های ذخیره‌شده و پرسش‌ها. فقط چیزی را نگه می‌داریم که برای کار کردن همان بخش لازم است.',
      'دادهٔ سلامت (مثل تاریخ پریود) حساس است: رمزگذاری‌شده نگه داشته می‌شود و فقط خودتان، و اگر خودتان اجازه دهید همسرتان، آن را می‌بینید.'
    ]
  },
  {
    t: 'چه کسی داده را می‌بیند',
    p: [
      'هیچ داده‌ای را نمی‌فروشیم و به تبلیغ‌کننده نمی‌دهیم. پرسش بی‌نام بدون نام و شماره به متخصص می‌رسد. در جامعهٔ والدین فقط نام نمایشی که خودتان انتخاب می‌کنید دیده می‌شود.'
    ]
  },
  {
    t: 'حذف داده',
    p: ['هر وقت بخواهید می‌توانید همهٔ داده‌هایتان را پاک کنید. در این نسخه، دکمهٔ پایین همهٔ داده‌های دردونه را از همین دستگاه پاک می‌کند.']
  }
];

export default function Privacy() {
  const [done, setDone] = useState(false);
  const wipe = () => {
    if (!window.confirm('همهٔ داده‌های دردونه روی این دستگاه پاک شود؟ این کار برگشت ندارد.')) return;
    try {
      Object.keys(localStorage)
        .filter((k) => k.startsWith('dordooneh:'))
        .forEach((k) => localStorage.removeItem(k));
      sessionStorage.removeItem('dordooneh:story');
    } catch {
      /* حافظه در دسترس نیست؛ چیزی هم ذخیره نشده */
    }
    window.dispatchEvent(new CustomEvent('dordooneh:store'));
    setDone(true);
  };
  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title="حریم خصوصی"
          intro="به زبان ساده: چه داده‌ای نگه می‌داریم، چه کسی می‌بیند و چطور پاکش کنید."
          crumbs={[{ label: 'حریم خصوصی' }]}
        />
        <div className="stack prose narrow">
          <p className="notice notice--warn">
            <Icon name="Info" size={18} />
            <span>این متن نسخهٔ اولیه است و پیش از راه‌اندازی رسمی با مشاور حقوقی نهایی می‌شود.</span>
          </p>
          {sections.map((s) => (
            <section key={s.t}>
              <h2 className="section-title">{s.t}</h2>
              {s.p.map((x) => (
                <p key={x}>{x}</p>
              ))}
            </section>
          ))}
          <div className="actions">
            <button type="button" className="btn btn--line" onClick={wipe}>
              <Icon name="Trash2" size={18} />
              پاک کردن همهٔ داده‌های من از این دستگاه
            </button>
            {done && (
              <p className="success" role="status">
                <Icon name="Check" size={18} />
                پاک شد.
              </p>
            )}
          </div>
        </div>
      </div>
    </main>
  );
}
