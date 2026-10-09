import { useId, useState } from 'react';
import { tipFor } from '../lib/daily.js';
import { href, navigate } from '../lib/router.js';
import { useStored } from '../lib/store.js';
import Icon from './Icon.jsx';

const STAGES = [
  { label: 'قبل از ازدواج', path: '/stage/before', icon: 'HeartHandshake' },
  { label: 'اقدام به بارداری', path: '/stage/trying', icon: 'CalendarDays' },
  { label: 'بارداری', path: '/stage/pregnancy', icon: 'HeartPulse' },
  { label: 'نوزاد', path: '/stage/baby', icon: 'Baby' },
  { label: 'کودک', path: '/stage/child', icon: 'Sparkles' },
  { label: 'پدرها', path: '/men', icon: 'User' }
];

/* بلافاصله بعد از قصه‌ساز: هر کس مسیر خودش را پیدا کند */
export default function StagePicker() {
  const id = useId();
  const [q, setQ] = useState('');
  const [profile] = useStored('profile', {});
  const known = profile.onboarded || profile.stage;
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
        <div className="stages-band__me">
          <p className="stages-band__tip">
            <span className="tag">پیام امروز</span> {tipFor(profile.stage || 'general')}
          </p>
          <a className="btn btn--line" href={href(known ? '/me' : '/welcome')}>
            <Icon name="User" size={18} />
            {known ? 'داشبورد من' : 'سه سؤال، تا دردونه مال خودتان شود'}
          </a>
        </div>
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
