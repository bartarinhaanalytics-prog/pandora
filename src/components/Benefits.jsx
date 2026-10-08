import { benefits } from '../data/content.js';
import Icon from './Icon.jsx';
import './Benefits.css';

export default function Benefits() {
  return (
    <section id="benefits" className="section benefits" aria-labelledby="benefits-title">
      <div className="container">
        <div className="section-head">
          <p className="eyebrow">چرا دردونه</p>
          <h2 id="benefits-title" className="h2">
            جواب مطمئن، برای سؤال‌هایی که روی پرسیدنش را ندارید
          </h2>
        </div>
        <ul className="benefits__grid" role="list">
          {benefits.map((b, i) => (
            <li key={b.title} className={`benefit benefit--${i + 1}`}>
              <span className="benefit__icon" aria-hidden="true">
                <Icon name={b.icon} size={26} strokeWidth={1.75} />
              </span>
              <h3 className="benefit__title">{b.title}</h3>
              <p className="benefit__text">{b.text}</p>
            </li>
          ))}
        </ul>
      </div>
    </section>
  );
}
