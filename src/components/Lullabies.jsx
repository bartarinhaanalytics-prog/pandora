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
                <span className="verse__title">{l.title}</span>
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
        <a className="btn btn--line lull__more" href={href('/lullabies')}>
          همهٔ لالایی‌ها
        </a>
      </div>
    </section>
  );
}
