import { lullabies, lullabyCats } from '../data/content.js';
import { href } from '../lib/router.js';
import Icon from '../components/Icon.jsx';
import SaveButton from '../components/SaveButton.jsx';
import StoryReader from '../components/StoryReader.jsx';
import NotFound from './NotFound.jsx';

/* صفحهٔ هر لالایی؛ با همان حالت خواندن شب قصه‌ها */
export default function LullabyPage({ id }) {
  const l = lullabies.find((x) => x.id === id);
  if (!l) return <NotFound />;
  const similar = lullabies.filter((x) => x.id !== id && x.cat === l.cat).concat(lullabies.filter((x) => x.id !== id && x.cat !== l.cat)).slice(0, 3);
  const lines = l.translation ? l.lines.map((line, i) => `${line}\n(${l.translation[i]})`) : l.lines;
  return (
    <StoryReader
      title={l.title}
      parts={lines}
      back={{ path: '/lullabies', label: 'کتابخانهٔ لالایی' }}
      meta={`${l.origin} · ${lullabyCats.find((c) => c.id === l.cat)?.label || ''}`}
      actions={
        <div className="stack" style={{ width: '100%' }}>
          {l.partial && <p className="hint">بیت‌های بیشتر پس از بازبینی منبع اضافه می‌شود.</p>}
          <p className="notice">
            <Icon name="Music" size={18} />
            <span>صدای این لالایی هنوز ضبط نشده؛ پخش‌کننده با تایمر خاموشی همراه صدا اضافه می‌شود.</span>
          </p>
          <section>
            <h2 className="section-title">دربارهٔ این لالایی</h2>
            <p className="hint" style={{ fontSize: '1rem' }}>{l.about}</p>
          </section>
          <div className="actions">
            <SaveButton type="lullaby" id={l.id} title={l.title} />
          </div>
          <section>
            <h2 className="section-title">لالایی‌های دیگر</h2>
            <div className="chips">
              {similar.map((s) => (
                <a key={s.id} className="chip" href={href(`/lullaby/${s.id}`)}>
                  {s.title}
                </a>
              ))}
            </div>
          </section>
        </div>
      }
    />
  );
}
