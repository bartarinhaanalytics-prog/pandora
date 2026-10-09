import { lullabies, lullabyCats } from '../data/content.js';
import { href } from '../lib/router.js';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';
import './LullabyLibrary.css';

export default function LullabyLibrary({ query }) {
  const cat = query.get('cat') || '';
  const list = lullabies.filter((l) => !cat || l.cat === cat);
  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title="کتابخانهٔ لالایی"
          intro="لالایی‌های قدیمی و محلی ایران با متن، برای وقتی که قصه تمام شده و چشم‌ها هنوز باز است."
          crumbs={[{ label: 'لالایی' }]}
        />
        <p className="notice" style={{ marginBottom: 'var(--s-5)', maxWidth: 760 }}>
          <Icon name="Music" size={18} />
          <span>صدای لالایی‌ها هنوز ضبط نشده و به‌زودی اضافه می‌شود. فعلاً متن را بخوانید یا برای بچه بخوانید.</span>
        </p>
        <nav className="chips" aria-label="دسته" style={{ marginBottom: 'var(--s-6)' }}>
          <a className="chip" href={href('/lullabies')} aria-current={!cat ? 'true' : undefined}>
            همه
          </a>
          {lullabyCats.map((c) => (
            <a key={c.id} className="chip" href={href('/lullabies', { cat: c.id })} aria-current={cat === c.id ? 'true' : undefined}>
              {c.label}
            </a>
          ))}
        </nav>
        <div className="lib">
          {list.map((l) => (
            <article key={l.id} className="lib__item" id={`l-${l.id}`} aria-labelledby={`lt-${l.id}`}>
              <header>
                <h2 id={`lt-${l.id}`} className="lib__title">
                  {l.title}
                </h2>
                <p className="row__meta">
                  <span className="tag">{l.origin}</span>
                </p>
                <p className="lib__about">{l.about}</p>
              </header>
              <blockquote className="lib__verse">
                {l.lines.map((line, i) => (
                  <p key={line}>
                    {line}
                    {l.translation && <span className="lib__tr">{l.translation[i]}</span>}
                  </p>
                ))}
              </blockquote>
              {l.partial && <p className="hint">بیت‌های بیشتر پس از بازبینی منبع اضافه می‌شود.</p>}
            </article>
          ))}
        </div>
      </div>
    </main>
  );
}
