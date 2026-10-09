import { reviewers } from '../data/content.js';
import { href } from '../lib/router.js';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';

export default function About() {
  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title="دربارهٔ دردونه"
          intro="دردونه همراه رایگان خانواده‌های ایرانی است؛ از روزهای آمادگی برای ازدواج تا شب‌هایی که بچه بدون قصه خوابش نمی‌برد."
          crumbs={[{ label: 'دربارهٔ ما' }]}
        />
        <div className="two-col">
          <div className="stack prose">
            <section>
              <h2 className="section-title">چرا دردونه را ساختیم</h2>
              <p>
                خیلی از پرسش‌های سلامت را آدم روی‌اش نمی‌شود از کسی بپرسد؛ دربارهٔ رابطه، بارداری یا نوزادی که تا صبح گریه می‌کند. جواب‌های
                اینترنت هم پراکنده و گاهی ترسناک‌اند. دردونه این پاسخ‌ها را به زبان ساده و مرحله‌به‌مرحله کنار هم می‌گذارد.
              </p>
              <p>
                کنارش، قصه‌ساز و لالایی‌ها هستند؛ چون سلامت خانواده فقط دارو و آزمایش نیست. شبی که پدر یا مادر با اسم بچه‌اش قصه می‌سازد،
                شب خوبی است.
              </p>
            </section>
            <section>
              <h2 className="section-title">چه چیزهایی قول می‌دهیم</h2>
              <ul className="promises">
                <li>
                  <Icon name="ShieldCheck" size={20} />
                  <span>
                    <strong>همه‌چیز رایگان است.</strong> اشتراک ویژه و بخش پولی نداریم.
                  </span>
                </li>
                <li>
                  <Icon name="Lock" size={20} />
                  <span>
                    <strong>دادهٔ سلامت شما مال خودتان است.</strong> آن را نمی‌فروشیم و برای تبلیغ هدفمند استفاده نمی‌کنیم.{' '}
                    <a href={href('/privacy')}>حریم خصوصی</a>
                  </span>
                </li>
                <li>
                  <Icon name="Stethoscope" size={20} />
                  <span>
                    <strong>جای پزشک را نمی‌گیریم.</strong> هر جا لازم باشد صریح می‌گوییم که باید به پزشک بروید.
                  </span>
                </li>
              </ul>
            </section>
          </div>
          <aside className="panel">
            <h2 className="section-title">پزشکان بازبین</h2>
            <p className="hint">
              تیم بازبینی در حال شکل گرفتن است. تا وقتی نام کسی قطعی نشده، جای آن خالی می‌ماند؛ نام ساختگی نمی‌نویسیم.
            </p>
            <div className="rows">
              {reviewers.map((r) => (
                <div key={r.role} className="row">
                  <span className="row__title">{r.role}</span>
                  <span className="row__meta">
                    {r.topic}
                    <span className="tag">نام: هنوز اعلام نشده</span>
                  </span>
                </div>
              ))}
            </div>
          </aside>
        </div>
      </div>
    </main>
  );
}
