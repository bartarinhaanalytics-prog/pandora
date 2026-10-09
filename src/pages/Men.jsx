import { articles, categories } from '../data/articles.js';
import { qa } from '../data/qa.js';
import { href } from '../lib/router.js';
import ArticleItem from '../components/ArticleItem.jsx';
import Draft from '../components/Draft.jsx';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';
import './Health.css';

export default function Men({ query }) {
  const cat = categories.find((c) => c.id === 'men');
  const menQa = qa.filter((x) => x.stage === 'men');
  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title="سلامت مردان و نقش پدر"
          intro="باروری و سلامت جنسی مردان، و این‌که پدر در بارداری و روزهای اول نوزاد چه کاری می‌تواند بکند؛ بی‌نام و بدون قضاوت."
          crumbs={[{ label: 'سلامت', path: '/health' }, { label: 'مردان و پدرها' }]}
        />
        <nav className="chips men-jump" aria-label="بخش‌های این صفحه">
          {cat.subs.map((s) => (
            <a key={s.id} className="chip" href={`#men-${s.id}`} onClick={(e) => { e.preventDefault(); document.getElementById(`men-${s.id}`)?.scrollIntoView(); }}>
              {s.label}
            </a>
          ))}
        </nav>
        <div className="two-col">
          <div className="stack">
            {cat.subs.map((s) => (
              <section key={s.id} aria-labelledby={`men-${s.id}`}>
                <h2 id={`men-${s.id}`} className="section-title">
                  {s.label}
                </h2>
                <div className="arts">
                  {articles
                    .filter((a) => a.cat === 'men' && a.sub === s.id)
                    .map((a) => (
                      <ArticleItem key={a.id} a={a} />
                    ))}
                </div>
              </section>
            ))}
          </div>
          <aside className="panel stack">
            <h2 className="section-title">پرسش‌هایی که مردها می‌پرسند</h2>
            <div>
              {menQa.map((x) => (
                <details key={x.id} className="acc">
                  <summary>
                    <span>{x.q}</span>
                    <Icon name="ChevronDown" size={20} />
                  </summary>
                  <div className="acc__body">
                    <p>{x.a}</p>
                    <Draft />
                  </div>
                </details>
              ))}
            </div>
            <a className="btn btn--line" href={href('/ask')}>
              <Icon name="Lock" size={18} />
              سؤال خودم را بی‌نام بپرسم
            </a>
          </aside>
        </div>
      </div>
    </main>
  );
}
