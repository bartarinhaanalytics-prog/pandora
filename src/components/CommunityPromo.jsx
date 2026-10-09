import { useId } from 'react';
import { href } from '../lib/router.js';
import { groups } from '../pages/Community.jsx';
import Icon from './Icon.jsx';

export default function CommunityPromo() {
  const id = useId();
  return (
    <section className="home-sec comm" aria-labelledby={`${id}-t`}>
      <div className="wrap comm__inner">
        <div className="home-sec__copy">
          <h2 id={`${id}-t`}>تنها نیستید</h2>
          <p>با پدر و مادرهایی که بچه‌شان هم‌سن بچهٔ شماست گفتگو کنید، یا سؤالتان را بی‌نام از متخصص بپرسید.</p>
          <ul className="comm__groups">
            {groups.map((g) => (
              <li key={g.id}>
                <a href={href(`/community/${g.id}`)}>{g.label}</a>
              </li>
            ))}
          </ul>
          <a className="btn btn--line" href={href('/community')}>
            <Icon name="MessagesSquare" size={18} />
            جامعهٔ والدین
          </a>
        </div>
        <div className="panel comm__ask">
          <Icon name="Lock" size={28} />
          <h3>پرسش بی‌نام از متخصص</h3>
          <p>نام و شماره‌تان به متخصص نمی‌رسد. سؤال‌هایی دربارهٔ رابطه، بارداری یا بچه که روی‌تان نمی‌شود از کسی بپرسید.</p>
          <a className="btn btn--star" href={href('/ask')}>
            سؤالم را بپرسم
          </a>
        </div>
      </div>
    </section>
  );
}
