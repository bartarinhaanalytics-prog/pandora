import { articles } from '../data/articles.js';
import { lullabies } from '../data/content.js';
import { readyStories } from '../data/readyStories.js';
import { formatJ, fromISO } from '../lib/jalali.js';
import { href } from '../lib/router.js';
import { useStored } from '../lib/store.js';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';

const KINDS = {
  article: { label: 'مقاله‌ها', path: (id) => `/article/${id}`, exists: (id) => articles.some((a) => a.id === id) },
  lullaby: { label: 'لالایی‌ها', path: (id) => `/lullaby/${id}`, exists: (id) => lullabies.some((l) => l.id === id) },
  story: { label: 'قصه‌های آماده', path: (id) => `/stories/${id}`, exists: (id) => readyStories.some((s) => s.id === id) }
};

export default function Saved() {
  const [saved, setSaved] = useStored('saved', []);
  const items = saved.filter((s) => KINDS[s.type]?.exists(s.id));
  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead title="محتوای ذخیره‌شده" intro="مقاله‌ها، لالایی‌ها و قصه‌هایی که نشان کرده‌اید." crumbs={[{ label: 'من', path: '/me' }, { label: 'ذخیره‌شده‌ها' }]} />
        {items.length ? (
          <div className="narrow stack">
            {Object.entries(KINDS).map(([type, k]) => {
              const list = items.filter((s) => s.type === type);
              if (!list.length) return null;
              return (
                <section key={type}>
                  <h2 className="section-title">{k.label}</h2>
                  <ul className="rows" style={{ listStyle: 'none', padding: 0, margin: 0 }}>
                    {list.map((s) => (
                      <li key={s.id} className="row">
                        <a className="row__title" href={href(k.path(s.id))}>
                          {s.title}
                        </a>
                        <span className="row__meta">
                          {formatJ(fromISO(s.date))}
                          <button type="button" className="linkish" onClick={() => setSaved(saved.filter((x) => !(x.type === s.type && x.id === s.id)))}>
                            <Icon name="X" size={16} />
                            برداشتن
                          </button>
                        </span>
                      </li>
                    ))}
                  </ul>
                </section>
              );
            })}
          </div>
        ) : (
          <div className="empty">
            <p>هنوز چیزی ذخیره نکرده‌اید. در صفحهٔ هر مقاله، لالایی یا قصهٔ آماده، دکمهٔ «ذخیره» را بزنید.</p>
            <div className="actions">
              <a className="btn btn--line" href={href('/health')}>
                مقاله‌های سلامت
              </a>
              <a className="btn btn--line" href={href('/stories')}>
                قصه‌های آماده
              </a>
            </div>
          </div>
        )}
      </div>
    </main>
  );
}
