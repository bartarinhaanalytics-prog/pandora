import { useState } from 'react';
import { formatJ, fromISO } from '../lib/jalali.js';
import { href } from '../lib/router.js';
import { decodeStory, shareText, storyLink } from '../lib/share.js';
import { useStored } from '../lib/store.js';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';
import StoryReader from '../components/StoryReader.jsx';
import NotFound from './NotFound.jsx';

export function ShareButton({ story }) {
  const [copied, setCopied] = useState(false);
  return (
    <>
      <button
        type="button"
        className="btn btn--line"
        onClick={async () => {
          try {
            setCopied(await shareText({ title: story.title, text: `قصهٔ «${story.title}» را ببینید:`, url: storyLink(story) }));
          } catch {
            /* لغو شد */
          }
        }}
      >
        <Icon name="Share2" size={18} />
        فرستادن برای خانواده
      </button>
      {copied && (
        <span className="success" role="status">
          <Icon name="Check" size={18} />
          لینک قصه کپی شد
        </span>
      )}
    </>
  );
}

/* قصه‌های من: قصه‌های ذخیره‌شده با برچسب اسم بچه */
export function MyStories() {
  const [stories, setStories] = useStored('stories', []);
  const [who, setWho] = useState('');
  const names = [...new Set(stories.map((s) => s.name).filter(Boolean))];
  const list = stories.filter((s) => !who || s.name === who);
  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead title="قصه‌های من" intro="قصه‌هایی که ساخته و ذخیره کرده‌اید؛ برای شب‌هایی که بچه همان قصهٔ قبلی را می‌خواهد." crumbs={[{ label: 'من', path: '/me' }, { label: 'قصه‌های من' }]} />
        {names.length > 1 && (
          <div className="chips" role="group" aria-label="بچه" style={{ marginBottom: 'var(--s-5)' }}>
            <button type="button" className="chip" aria-pressed={!who} onClick={() => setWho('')}>
              همه
            </button>
            {names.map((n) => (
              <button key={n} type="button" className="chip" aria-pressed={who === n} onClick={() => setWho(n)}>
                {n}
              </button>
            ))}
          </div>
        )}
        {list.length ? (
          <ul className="rows narrow" style={{ listStyle: 'none', padding: 0, margin: 0 }}>
            {list.map((s) => (
              <li key={s.id} className="row">
                <a className="row__title" href={href(`/story/read/${s.id}`)}>
                  {s.title}
                </a>
                <span className="row__meta">
                  {s.name && <span className="tag">{s.name}</span>}
                  {formatJ(fromISO(s.date))}
                  <button
                    type="button"
                    className="linkish"
                    onClick={() => {
                      if (window.confirm(`«${s.title}» حذف شود؟`)) setStories(stories.filter((x) => x.id !== s.id));
                    }}
                  >
                    <Icon name="Trash2" size={16} />
                    حذف
                  </button>
                </span>
              </li>
            ))}
          </ul>
        ) : (
          <div className="empty">
            <p>هنوز قصه‌ای ذخیره نکرده‌اید.</p>
            <div className="actions">
              <a className="btn btn--star" href={href('/story')}>
                <Icon name="BookHeart" size={18} />
                ساختن قصه
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

export function ReadSaved({ id }) {
  const [stories] = useStored('stories', []);
  const s = stories.find((x) => x.id === id);
  if (!s) return <NotFound />;
  return (
    <StoryReader
      title={s.title}
      parts={s.parts}
      back={{ path: '/me/stories', label: 'قصه‌های من' }}
      meta={formatJ(fromISO(s.date))}
      actions={<ShareButton story={s} />}
    />
  );
}

/* صفحهٔ عمومی قصهٔ فرستاده‌شده: پدربزرگ و مادربزرگ بدون ثبت‌نام می‌بینند */
export function SharedStory({ data }) {
  const s = decodeStory(data || '');
  if (!s) return <NotFound />;
  return (
    <StoryReader
      title={s.title}
      parts={s.parts}
      back={{ path: '/', label: 'دردونه' }}
      meta="این قصه با قصه‌ساز دردونه ساخته شده است."
      actions={
        <div className="panel stack" style={{ width: '100%' }}>
          <h2 className="section-title">قصهٔ بچهٔ خودتان را بسازید</h2>
          <p className="hint">اسم بچه را بنویسید، موضوع و قهرمان را انتخاب کنید؛ رایگان و بدون ثبت‌نام.</p>
          <a className="btn btn--star" href={href('/story')}>
            <Icon name="BookHeart" size={18} />
            ساختن قصه در دردونه
          </a>
        </div>
      }
    />
  );
}
