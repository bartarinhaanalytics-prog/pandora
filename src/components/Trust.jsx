import { reviewers } from '../data/content.js';
import Icon from './Icon.jsx';
import './Trust.css';

export default function Trust() {
  return (
    <section id="trust" className="trust" aria-labelledby="trust-title">
      <div className="wrap trust__inner">
        <div className="trust__copy">
          <h2 id="trust-title">چه کسی پاسخ‌ها را بازبینی می‌کند؟</h2>
          <p>
            هر پاسخ سلامت پیش از انتشار به دست متخصص همان رشته خوانده و اصلاح می‌شود. تیم بازبینی در حال شکل گرفتن است و تا وقتی نام
            کسی قطعی نشده، جای آن خالی می‌ماند؛ نام ساختگی نمی‌نویسیم.
          </p>
          <ul className="trust__promises">
            <li>
              <Icon name="ShieldCheck" size={20} />
              <span>
                <strong>همه‌چیز رایگان است.</strong> قصه‌ساز، پرسش‌های سلامت و لالایی‌ها؛ بدون اشتراک ویژه.
              </span>
            </li>
            <li>
              <Icon name="ShieldCheck" size={20} />
              <span>
                <strong>خواندن بی‌نام است.</strong> برای خواندن پاسخ‌ها لازم نیست ثبت‌نام کنید.
              </span>
            </li>
            <li>
              <Icon name="ShieldCheck" size={20} />
              <span>
                <strong>جای پزشک را نمی‌گیرد.</strong> هرجا لازم باشد، صریح می‌گوییم که باید به پزشک بروید.
              </span>
            </li>
          </ul>
        </div>

        <div className="roster quilt">
          <h3 className="roster__title">بازبین‌ها</h3>
          <dl>
            {reviewers.map((r) => (
              <div key={r.role} className="roster__row">
                <dt>{r.role}</dt>
                <dd>
                  <span className="roster__topic">{r.topic}</span>
                  <span className="roster__name">نام: هنوز اعلام نشده</span>
                </dd>
              </div>
            ))}
          </dl>
        </div>
      </div>
    </section>
  );
}
