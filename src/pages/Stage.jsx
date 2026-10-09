import { articles } from '../data/articles.js';
import { qa } from '../data/qa.js';
import { stages, stageById } from '../data/stages.js';
import { href } from '../lib/router.js';
import { useStored } from '../lib/store.js';
import ArticleItem from '../components/ArticleItem.jsx';
import Draft from '../components/Draft.jsx';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';
import NotFound from './NotFound.jsx';
import './Health.css';

/* «مسیر من»: همهٔ محتوای یک مرحله در یک صفحه */
export default function Stage({ id }) {
  const st = stageById(id);
  const [profile, setProfile] = useStored('profile', {});
  if (!st) return <NotFound />;
  const arts = articles.filter((a) => a.stage === id || (a.stage === 'all' && st.cats.includes(a.cat))).slice(0, 8);
  const faq = qa.filter((x) => x.stage === id).slice(0, 4);
  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead title={st.label} intro={st.intro} crumbs={[{ label: 'مسیر من' }, { label: st.short }]} />
        <nav className="chips" aria-label="مرحله‌های دیگر" style={{ marginBottom: 'var(--s-6)' }}>
          {stages.map((s) => (
            <a key={s.id} className="chip" href={href(`/stage/${s.id}`)} aria-current={s.id === id ? 'true' : undefined}>
              {s.short}
            </a>
          ))}
        </nav>
        <div className="two-col">
          <div className="stack">
            {id === 'pregnancy' && (
              <section>
                <h2 className="section-title">زیرمرحله‌ها</h2>
                <div className="chips">
                  <a className="chip" href={href('/pregnancy/8')}>سه‌ماههٔ اول</a>
                  <a className="chip" href={href('/pregnancy/20')}>سه‌ماههٔ دوم</a>
                  <a className="chip" href={href('/pregnancy/32')}>سه‌ماههٔ سوم</a>
                </div>
              </section>
            )}
            <section>
              <h2 className="section-title">مطالب مهم این مرحله</h2>
              <div className="arts">
                {arts.map((a) => (
                  <ArticleItem key={a.id} a={a} />
                ))}
              </div>
            </section>
            {faq.length > 0 && (
              <section>
                <h2 className="section-title">پرسش‌های پرتکرار</h2>
                {faq.map((x) => (
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
              </section>
            )}
          </div>
          <aside className="panel stack">
            <h2 className="section-title">ابزارهای این مرحله</h2>
            <nav className="me-links" aria-label="ابزارها" style={{ padding: 0 }}>
              {st.tools.map((t) => (
                <a key={t.path} href={href(t.path)}>
                  <Icon name="Sparkles" size={20} />
                  <span>
                    <strong>{t.label}</strong>
                  </span>
                </a>
              ))}
            </nav>
            {profile.stage === id ? (
              <p className="success">
                <Icon name="Check" size={18} />
                این مرحلهٔ شماست؛ داشبوردتان بر اساس آن تنظیم شده.
              </p>
            ) : (
              <button type="button" className="btn btn--line" onClick={() => setProfile({ ...profile, stage: id })}>
                این مرحلهٔ من است
              </button>
            )}
          </aside>
        </div>
      </div>
    </main>
  );
}
