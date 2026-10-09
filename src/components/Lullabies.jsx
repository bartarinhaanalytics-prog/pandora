import { lullabies } from '../data/content.js';
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
          {lullabies.map((l) => (
            <figure key={l.id} className={`verse ${l.pending ? 'verse--pending' : ''}`}>
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
      </div>
    </section>
  );
}
