import { readyStories, storyAges, storyTopics } from '../data/readyStories.js';
import { faNum } from '../lib/fa.js';
import { href } from '../lib/router.js';
import PageHead from '../components/PageHead.jsx';
import SaveButton from '../components/SaveButton.jsx';
import StoryReader from '../components/StoryReader.jsx';
import NotFound from './NotFound.jsx';
import { ShareButton } from './StoryPages.jsx';

const label = (list, id) => list.find((x) => x.id === id)?.label || '';

export function ReadyStories({ query }) {
  const age = query.get('age') || '';
  const topic = query.get('topic') || '';
  const q = (patch) => href('/stories', Object.fromEntries(Object.entries({ age, topic, ...patch }).filter(([, v]) => v)));
  const list = readyStories.filter((s) => (!age || s.age === age) && (!topic || s.topic === topic));
  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title="قصه‌های آماده"
          intro="برای شب‌هایی که حوصلهٔ ساختن قصه نیست؛ قصه‌های کوتاه ایرانی بر اساس سن و موضوع."
          crumbs={[{ label: 'قصه', path: '/story' }, { label: 'قصه‌های آماده' }]}
        />
        <div className="stack" style={{ marginBottom: 'var(--s-6)', gap: 'var(--s-3)' }}>
          <nav className="chips" aria-label="سن">
            <a className="chip" href={q({ age: '' })} aria-current={!age ? 'true' : undefined}>
              همهٔ سن‌ها
            </a>
            {storyAges.map((a) => (
              <a key={a.id} className="chip" href={q({ age: a.id })} aria-current={age === a.id ? 'true' : undefined}>
                {a.label}
              </a>
            ))}
          </nav>
          <nav className="chips" aria-label="موضوع">
            <a className="chip" href={q({ topic: '' })} aria-current={!topic ? 'true' : undefined}>
              همهٔ موضوع‌ها
            </a>
            {storyTopics.map((t) => (
              <a key={t.id} className="chip" href={q({ topic: t.id })} aria-current={topic === t.id ? 'true' : undefined}>
                {t.label}
              </a>
            ))}
          </nav>
        </div>
        {list.length ? (
          <ul className="rows narrow" style={{ listStyle: 'none', padding: 0, margin: 0 }}>
            {list.map((s) => (
              <li key={s.id} className="row">
                <a className="row__title" href={href(`/stories/${s.id}`)} style={{ fontSize: '1.25rem' }}>
                  {s.title}
                </a>
                <span className="row__meta">
                  <span className="tag">{label(storyAges, s.age)}</span>
                  <span className="tag">{label(storyTopics, s.topic)}</span>
                  {faNum(s.minutes)} دقیقه
                </span>
                <p>{s.summary}</p>
              </li>
            ))}
          </ul>
        ) : (
          <div className="empty">
            <p>قصه‌ای با این سن و موضوع نداریم.</p>
            <a className="btn btn--line" href={href('/stories')}>
              همهٔ قصه‌ها
            </a>
          </div>
        )}
      </div>
    </main>
  );
}

export function ReadyStory({ id }) {
  const s = readyStories.find((x) => x.id === id);
  if (!s) return <NotFound />;
  return (
    <StoryReader
      title={s.title}
      parts={s.parts}
      back={{ path: '/stories', label: 'قصه‌های آماده' }}
      meta={`${label(storyAges, s.age)} · ${label(storyTopics, s.topic)} · ${faNum(s.minutes)} دقیقه`}
      actions={
        <>
          <SaveButton type="story" id={s.id} title={s.title} />
          <ShareButton story={s} />
        </>
      }
    />
  );
}
