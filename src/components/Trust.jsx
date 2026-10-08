import { trust } from '../data/content.js';
import Icon from './Icon.jsx';
import './Trust.css';

export default function Trust() {
  return (
    <section id="trust" className="section trust" aria-labelledby="trust-title">
      <div className="container">
        <div className="section-head">
          <p className="eyebrow">پزشکان و کاربران</p>
          <h2 id="trust-title" className="h2">
            پشت هر مقاله، یک متخصص با نام و تخصص
          </h2>
          <p className="lead">
            دردونه هنوز در مرحلهٔ آماده‌سازی است. جاهای خالی زیر پیش از انتشار با اطلاعات واقعی پر می‌شوند؛ هیچ نام یا نظری ساختگی نیست.
          </p>
        </div>

        <h3 className="trust__sub">پزشکان بازبین</h3>
        <ul className="reviewers" role="list">
          {trust.reviewers.map((r) => (
            <li key={r.role} className="reviewer">
              <span className="reviewer__avatar" aria-hidden="true">
                {r.initial}
              </span>
              <span className="reviewer__body">
                <span className="reviewer__name placeholder">[نام پزشک]</span>
                <span className="reviewer__role">{r.role}</span>
              </span>
              <Icon name="ShieldCheck" size={20} className="reviewer__badge" />
            </li>
          ))}
        </ul>

        <h3 className="trust__sub">نظر کاربران</h3>
        <ul className="quotes" role="list">
          {trust.testimonials.map((t) => (
            <li key={t.slot} className="quote">
              <p className="quote__text placeholder">[{t.hint}]</p>
              <p className="quote__who">{t.slot}</p>
            </li>
          ))}
        </ul>
      </div>
    </section>
  );
}
