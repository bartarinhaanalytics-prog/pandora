import { useId, useMemo, useState } from 'react';
import { articles, categories } from '../data/articles.js';
import { lullabies } from '../data/content.js';
import { weeks } from '../data/pregnancy.js';
import { qa } from '../data/qa.js';
import { sitemap } from '../data/site.js';
import { faNum, normalize } from '../lib/fa.js';
import { href, navigate } from '../lib/router.js';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';

const POPULAR = ['تب نوزاد', 'تاخیر پریود', 'آزمایش قبل از ازدواج', 'رابطه در بارداری', 'دندان درآوردن', 'لالایی'];

/* فهرست جستجو یک بار ساخته می‌شود */
const INDEX = [
  ...articles.map((a) => ({
    type: 'مقاله',
    title: a.title,
    text: a.summary,
    hay: normalize(`${a.title} ${a.summary} ${a.body.join(' ')}`),
    link: a.cat === 'men' ? href('/men', { a: a.id }) : href(`/health/${a.cat}`, { a: a.id }),
    meta: categories.find((c) => c.id === a.cat)?.label
  })),
  ...qa.map((x) => ({ type: 'پرسش و پاسخ', title: x.q, text: x.a, hay: normalize(`${x.q} ${x.a}`), link: href('/qa', { stage: x.stage, q: x.id }) })),
  ...weeks.map((w) => ({
    type: 'بارداری',
    title: `هفتهٔ ${faNum(w.week)} بارداری`,
    text: w.baby,
    hay: normalize(`هفته ${w.week} بارداری ${w.size || ''} ${w.baby} ${w.mother} ${w.care}`),
    link: href(`/pregnancy/${w.week}`)
  })),
  ...lullabies.map((l) => ({ type: 'لالایی', title: l.title, text: l.about, hay: normalize(`لالایی ${l.title} ${l.lines.join(' ')} ${l.origin}`), link: href('/lullabies') })),
  ...sitemap.flatMap((g) => g.links.map((l) => ({ type: 'بخش‌ها و ابزارها', title: l.label, text: g.title, hay: normalize(`${l.label} ${g.title}`), link: href(l.path) })))
];

function run(q) {
  const words = normalize(q).split(' ').filter(Boolean);
  if (!words.length) return [];
  return INDEX.map((it) => {
    const t = normalize(it.title);
    let score = 0;
    for (const w of words) {
      if (t.includes(w)) score += 3;
      else if (it.hay.includes(w)) score += 1;
      else return null;
    }
    return { ...it, score };
  })
    .filter(Boolean)
    .sort((a, b) => b.score - a.score);
}

export default function Search({ query }) {
  const id = useId();
  const [q, setQ] = useState(query.get('q') || '');
  const results = useMemo(() => run(q), [q]);
  const groups = useMemo(() => {
    const m = new Map();
    results.slice(0, 40).forEach((r) => m.set(r.type, [...(m.get(r.type) || []), r]));
    return [...m.entries()];
  }, [results]);

  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead title="جستجو" crumbs={[{ label: 'جستجو' }]} />
        <form
          role="search"
          className="search-box"
          onSubmit={(e) => {
            e.preventDefault();
            navigate('/search', q ? { q } : undefined);
          }}
        >
          <label htmlFor={`${id}-q`} className="sr-only">
            جستجو در مقاله‌ها، پرسش‌ها، هفته‌های بارداری و لالایی‌ها
          </label>
          <Icon name="Search" size={22} />
          <input
            id={`${id}-q`}
            className="input search-box__input"
            type="search"
            value={q}
            autoFocus
            placeholder="مثلاً تب نوزاد"
            onChange={(e) => setQ(e.target.value)}
          />
        </form>

        {!q.trim() && (
          <div className="stack" style={{ marginTop: 'var(--s-6)' }}>
            <h2 className="section-title">جستجوهای پرتکرار</h2>
            <div className="chips">
              {POPULAR.map((p) => (
                <button key={p} type="button" className="chip" onClick={() => setQ(p)}>
                  {p}
                </button>
              ))}
            </div>
          </div>
        )}

        {q.trim() && (
          <div className="results" aria-live="polite">
            <p className="hint">{results.length ? `${faNum(results.length)} نتیجه` : ''}</p>
            {groups.map(([type, items]) => (
              <section key={type} aria-label={type}>
                <h2 className="section-title">{type}</h2>
                <ul className="rows" style={{ listStyle: 'none', padding: 0, margin: 0 }}>
                  {items.map((r) => (
                    <li key={r.link + r.title} className="row">
                      <a className="row__title" href={r.link}>
                        {r.title}
                      </a>
                      {r.meta && <span className="row__meta">{r.meta}</span>}
                      <p className="clamp2">{r.text}</p>
                    </li>
                  ))}
                </ul>
              </section>
            ))}
            {!results.length && (
              <div className="empty">
                <p>برای «{q}» چیزی پیدا نشد. واژهٔ ساده‌تری امتحان کنید یا یکی از دسته‌ها را ببینید.</p>
                <div className="chips">
                  {categories.map((c) => (
                    <a key={c.id} className="chip" href={href(c.id === 'men' ? '/men' : `/health/${c.id}`)}>
                      {c.label}
                    </a>
                  ))}
                </div>
                <a className="btn btn--line" href={href('/ask')}>
                  <Icon name="Lock" size={18} />
                  بی‌نام از متخصص بپرسید
                </a>
              </div>
            )}
          </div>
        )}
      </div>
    </main>
  );
}
