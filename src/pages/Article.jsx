import { useState } from 'react';
import { articles, categories } from '../data/articles.js';
import { faNum } from '../lib/fa.js';
import { href } from '../lib/router.js';
import { shareText } from '../lib/share.js';
import Draft from '../components/Draft.jsx';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';
import SaveButton from '../components/SaveButton.jsx';
import NotFound from './NotFound.jsx';
import './Article.css';

export default function Article({ id }) {
  const a = articles.find((x) => x.id === id);
  const [copied, setCopied] = useState(false);
  if (!a) return <NotFound />;
  const cat = categories.find((c) => c.id === a.cat);
  const sub = cat.subs.find((s) => s.id === a.sub);
  const catPath = a.cat === 'men' ? '/men' : `/health/${a.cat}`;
  const related = articles.filter((x) => x.id !== a.id && x.cat === a.cat).sort((x, y) => (y.sub === a.sub) - (x.sub === a.sub)).slice(0, 4);

  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead title={a.title} intro={a.summary} crumbs={[{ label: 'سلامت', path: '/health' }, { label: cat.label, path: catPath }, { label: sub?.label || 'مقاله' }]}>
          <p className="row__meta">
            <span className="tag">{sub?.label}</span>
            <span>{faNum(a.minutes)} دقیقه مطالعه</span>
          </p>
        </PageHead>
        <div className="art-page">
          <article className="art-page__body" aria-label={a.title}>
            <div className="reviewer">
              <Icon name="Stethoscope" size={22} />
              <div>
                <p className="reviewer__t">بازبینی پزشک</p>
                <p className="reviewer__d">هنوز بازبینی نشده؛ نام متخصص و تاریخ بازبینی پس از بازبینی این‌جا می‌آید.</p>
              </div>
            </div>
            <nav className="toc" aria-label="فهرست مطالب">
              <p className="label">در این مطلب</p>
              <ol>
                <li>
                  <a href="#art-text" onClick={(e) => { e.preventDefault(); document.getElementById('art-text').scrollIntoView(); }}>
                    متن مطلب
                  </a>
                </li>
                <li>
                  <a href="#art-doctor" onClick={(e) => { e.preventDefault(); document.getElementById('art-doctor').scrollIntoView(); }}>
                    کی به پزشک مراجعه کنم
                  </a>
                </li>
                <li>
                  <a href="#art-sources" onClick={(e) => { e.preventDefault(); document.getElementById('art-sources').scrollIntoView(); }}>
                    منابع
                  </a>
                </li>
              </ol>
            </nav>
            <section id="art-text" className="art-page__text">
              {a.body.map((p) => (
                <p key={p}>{p}</p>
              ))}
            </section>
            <section id="art-doctor" className="art__doctor">
              <h2>کی به پزشک مراجعه کنم</h2>
              <p>{a.doctor}</p>
            </section>
            <section id="art-sources" className="art-page__sources">
              <h2>منابع</h2>
              <p className="hint">فهرست منابع پس از بازبینی پزشک اضافه می‌شود.</p>
            </section>
            <Draft />
            <div className="actions">
              <SaveButton type="article" id={a.id} title={a.title} />
              <button
                type="button"
                className="btn btn--line"
                onClick={async () => {
                  try {
                    setCopied(await shareText({ title: a.title, text: a.title, url: window.location.href }));
                  } catch {
                    /* لغو شد */
                  }
                }}
              >
                <Icon name="Share2" size={18} />
                اشتراک
              </button>
              {copied && (
                <span className="success" role="status">
                  <Icon name="Check" size={18} />
                  لینک کپی شد
                </span>
              )}
            </div>
          </article>
          <aside className="panel art-page__side">
            <h2 className="section-title">مطالب مرتبط</h2>
            <ul className="rows" style={{ listStyle: 'none', padding: 0, margin: 0 }}>
              {related.map((r) => (
                <li key={r.id} className="row">
                  <a className="row__title" href={href(`/article/${r.id}`)}>
                    {r.title}
                  </a>
                  <span className="row__meta">{faNum(r.minutes)} دقیقه</span>
                </li>
              ))}
            </ul>
            <a className="btn btn--line" href={href('/ask')}>
              <Icon name="Lock" size={18} />
              سؤال بی‌نام بپرسید
            </a>
          </aside>
        </div>
      </div>
    </main>
  );
}
