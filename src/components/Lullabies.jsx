import { lullabies } from '../data/content.js';
import { href } from '../lib/router.js';
import './Lullabies.css';

export default function Lullabies() {
  return (
    <section id="lullaby" className="lull" aria-labelledby="lull-title">
      <div className="wrap">
        <div className="lull__head">
          <h2 id="lull-title">لالایی‌هایی که مادربزرگ‌ها می‌خواندند</h2>
          <p>
            لالایی‌های قدیمی و محلی ایران با متن کامل، برای وقتی که قصه تمام شده و چشم‌ها هنوز باز است. صدای لالایی‌ها به‌زودی اضافه
            می‌شود.
          </p>
        </div>
        <div className="lull__verses">
          {lullabies.slice(0, 3).map((l) => (
            <figure key={l.id} className={`verse`}>
              <figcaption>
                <a className="verse__title" href={href(`/lullaby/${l.id}`)}>{l.title}</a>
                <span className="verse__origin">{l.origin}</span>
              </figcaption>
              <blockquote>
                {l.lines.map((line) => (
                  <p key={line}>{line}</p>
                ))}
              </blockquote>
            </figure>
          ))}
        </div>
        <div className="lull__links">
          <a className="btn btn--line" href={href('/lullabies')}>
            همهٔ لالایی‌ها
          </a>
          <a className="btn btn--line" href={href('/stories')}>
            قصه‌های آماده
          </a>
          <a className="btn btn--line" href={href('/me/stories')}>
            قصه‌های من
          </a>
          <a className="btn btn--line" href={href('/sleep')}>
            روتین خواب کودک
          </a>
        </div>
      </div>
    </section>
  );
}
