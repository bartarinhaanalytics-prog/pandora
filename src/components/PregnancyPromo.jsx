import { useId, useState } from 'react';
import { weeks } from '../data/pregnancy.js';
import { faNum } from '../lib/fa.js';
import { fromISO } from '../lib/jalali.js';
import { href } from '../lib/router.js';
import { useStored } from '../lib/store.js';
import { pregnancyFrom } from '../pages/Pregnancy.jsx';
import Icon from './Icon.jsx';

export default function PregnancyPromo() {
  const id = useId();
  const [profile] = useStored('profile', {});
  const mine = pregnancyFrom(fromISO(profile.lmp));
  const [n, setN] = useState(mine?.week || 20);
  const w = weeks[n - 1];
  return (
    <section className="home-sec preg-promo" aria-labelledby={`${id}-t`}>
      <div className="wrap preg-promo__inner">
        <div className="home-sec__copy">
          <h2 id={`${id}-t`}>بارداری هفته‌به‌هفته</h2>
          <p>
            هر هفته جنین چقدر بزرگ شده، بدن مادر چه تغییری می‌کند، کدام آزمایش لازم است و پدر چه کاری می‌تواند بکند؛ با تقویم شمسی و
            محاسبهٔ تاریخ زایمان.
          </p>
          <a className="btn btn--star" href={href('/pregnancy')}>
            {mine ? `هفتهٔ ${faNum(mine.week)} من` : 'هفتهٔ بارداری‌ام را حساب کن'}
            <Icon name="ArrowLeft" size={18} />
          </a>
        </div>
        <div className="panel preg-promo__card">
          <label htmlFor={`${id}-w`} className="preg-promo__label">
            هفتهٔ <span className="preg-promo__n">{faNum(n)}</span>
          </label>
          <input
            id={`${id}-w`}
            type="range"
            min="1"
            max="40"
            value={n}
            onChange={(e) => setN(Number(e.target.value))}
            className="range"
            aria-valuetext={`هفتهٔ ${faNum(n)}`}
          />
          <p className="preg-promo__size">{w.size ? <>اندازهٔ {w.size}{w.weight ? `، حدود ${w.weight.replace('حدود ', '')}` : ''}</> : 'شمارش از اولین روز آخرین پریود'}</p>
          <p className="preg-promo__text">{w.baby}</p>
          <a href={href(`/pregnancy/${n}`)}>همهٔ نکته‌های هفتهٔ {faNum(n)}</a>
        </div>
      </div>
    </section>
  );
}
