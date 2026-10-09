import { articles, categories } from '../data/articles.js';
import { faNum } from '../lib/fa.js';
import { href } from '../lib/router.js';
import ArticleItem from '../components/ArticleItem.jsx';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';
import './Health.css';

const STAGES = [
  { id: '', label: 'همهٔ مرحله‌ها' },
  { id: 'before', label: 'پیش از ازدواج' },
  { id: 'trying', label: 'اقدام به بارداری' },
  { id: 'pregnancy', label: 'بارداری' },
  { id: 'baby', label: 'نوزاد' },
  { id: 'child', label: 'کودک' }
];

export function HealthIndex() {
  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title="دسته‌های سلامت"
          intro="بدون ثبت‌نام و بی‌نام بخوانید. هر متن به زبان ساده است و می‌گوید کی باید به پزشک بروید."
          crumbs={[{ label: 'سلامت' }]}
        />
        <ul className="cats">
          {categories.map((c) => {
            const count = articles.filter((a) => a.cat === c.id).length;
            return (
              <li key={c.id}>
                <a className="cat" href={href(c.id === 'men' ? '/men' : `/health/${c.id}`)}>
                  <span className="cat__label">{c.label}</span>
                  <span className="cat__intro">{c.intro}</span>
                  <span className="cat__meta">
                    {faNum(count)} مطلب · {c.subs.map((s) => s.label).join('، ')}
                  </span>
                  <Icon name="ChevronLeft" size={22} className="cat__go" />
                </a>
              </li>
            );
          })}
        </ul>
        <div className="health-more">
          <a className="btn btn--line" href={href('/pregnancy')}>
            بارداری هفته‌به‌هفته
          </a>
          <a className="btn btn--line" href={href('/qa')}>
            پرسش‌های سلامت
          </a>
          <a className="btn btn--line" href={href('/ask')}>
            پرسش بی‌نام از متخصص
          </a>
        </div>
      </div>
    </main>
  );
}

export function HealthCategory({ id, query }) {
  const cat = categories.find((c) => c.id === id);
  if (!cat) return null;
  const sub = query.get('sub') || '';
  const stage = query.get('stage') || '';
  const openId = query.get('a') || '';
  const list = articles.filter((a) => a.cat === id && (!sub || a.sub === sub) && (!stage || a.stage === stage || a.stage === 'all'));
  const q = (patch) => {
    const next = { sub, stage, ...patch };
    return href(`/health/${id}`, Object.fromEntries(Object.entries(next).filter(([, v]) => v)));
  };
  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead title={cat.label} intro={cat.intro} crumbs={[{ label: 'سلامت', path: '/health' }, { label: cat.label }]} />
        <div className="filters">
          <nav className="chips" aria-label="زیردسته">
            <a className="chip" href={q({ sub: '' })} aria-current={!sub ? 'true' : undefined}>
              همه
            </a>
            {cat.subs.map((s) => (
              <a key={s.id} className="chip" href={q({ sub: s.id })} aria-current={sub === s.id ? 'true' : undefined}>
                {s.label}
              </a>
            ))}
          </nav>
          <label className="filters__stage">
            <span>مرحله</span>
            <select className="select" value={stage} onChange={(e) => (window.location.hash = q({ stage: e.target.value }).slice(1))}>
              {STAGES.map((s) => (
                <option key={s.id} value={s.id}>
                  {s.label}
                </option>
              ))}
            </select>
          </label>
        </div>
        {list.length ? (
          <div className="arts">
            {list.map((a) => (
              <ArticleItem key={a.id} a={a} open={openId === a.id} />
            ))}
          </div>
        ) : (
          <div className="empty">
            <p>هنوز مطلبی در این زیردسته و مرحله نیست.</p>
            <a className="btn btn--line" href={q({ sub: '', stage: '' })}>
              دیدن همهٔ مطالب {cat.label}
            </a>
          </div>
        )}
      </div>
    </main>
  );
}
