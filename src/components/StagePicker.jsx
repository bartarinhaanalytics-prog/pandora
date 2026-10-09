import { useId, useState } from 'react';
import { href, navigate } from '../lib/router.js';
import Icon from './Icon.jsx';

const STAGES = [
  { label: 'قبل از ازدواج', path: '/health/premarital', icon: 'HeartHandshake' },
  { label: 'اقدام به بارداری', path: '/tools/cycle', icon: 'CalendarDays' },
  { label: 'بارداری', path: '/pregnancy', icon: 'HeartPulse' },
  { label: 'نوزاد و کودک', path: '/health/child', icon: 'Baby' },
  { label: 'پدرها', path: '/men', icon: 'User' }
];

/* بلافاصله بعد از قصه‌ساز: هر کس مسیر خودش را پیدا کند */
export default function StagePicker() {
  const id = useId();
  const [q, setQ] = useState('');
  return (
    <section className="stages-band" aria-labelledby={`${id}-t`}>
      <div className="wrap stages-band__inner">
        <h2 id={`${id}-t`} className="stages-band__title">
          دردونه فقط قصه نیست؛ شما در کدام مرحله‌اید؟
        </h2>
        <nav className="stages-band__list" aria-labelledby={`${id}-t`}>
          {STAGES.map((s) => (
            <a key={s.path} href={href(s.path)} className="stage-link">
              <Icon name={s.icon} size={22} />
              {s.label}
            </a>
          ))}
        </nav>
        <form
          role="search"
          className="search-box stages-band__search"
          onSubmit={(e) => {
            e.preventDefault();
            navigate('/search', q.trim() ? { q: q.trim() } : undefined);
          }}
        >
          <label htmlFor={`${id}-q`} className="sr-only">
            جستجو در دردونه
          </label>
          <Icon name="Search" size={22} />
          <input id={`${id}-q`} className="input search-box__input" type="search" placeholder="جستجو؛ مثلاً تب نوزاد یا تاخیر پریود" value={q} onChange={(e) => setQ(e.target.value)} />
        </form>
      </div>
    </section>
  );
}
