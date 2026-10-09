import { useState } from 'react';
import { formatJ, fromISO } from '../lib/jalali.js';
import { useStored } from '../lib/store.js';
import { href } from '../lib/router.js';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';
import StoryMaker from '../components/StoryMaker.jsx';

export default function Story() {
  const [stories, setStories] = useStored('stories', []);
  const [reading, setReading] = useState(null);
  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title="قصه‌ساز"
          intro="با موضوعی که خودتان می‌خواهید، برای فرزندتان قصهٔ شب بسازید؛ اسمش قهرمان قصه می‌شود."
          crumbs={[{ label: 'قصه‌ساز' }]}
        />
        <div className="two-col">
          <StoryMaker full />
          <aside className="panel stack" aria-labelledby="mine-title">
            <h2 id="mine-title" className="section-title">
              قصه‌های من
            </h2>
            {stories.length ? (
              <ul className="rows" style={{ listStyle: 'none', padding: 0, margin: 0 }}>
                {stories.map((s) => (
                  <li key={s.id} className="row">
                    <button type="button" className="linkish row__title" aria-expanded={reading === s.id} onClick={() => setReading(reading === s.id ? null : s.id)}>
                      {s.title}
                    </button>
                    <span className="row__meta">
                      {formatJ(fromISO(s.date))}
                      <button type="button" className="linkish" onClick={() => setStories(stories.filter((x) => x.id !== s.id))}>
                        <Icon name="Trash2" size={16} />
                        حذف
                      </button>
                    </span>
                    {reading === s.id && (
                      <div className="saved-tale">
                        {s.parts.map((p, i) => (
                          <p key={i}>{p}</p>
                        ))}
                      </div>
                    )}
                  </li>
                ))}
              </ul>
            ) : (
              <div className="empty">
                <p>هنوز قصه‌ای ذخیره نکرده‌اید. بعد از ساختن قصه، دکمهٔ «ذخیره» را بزنید.</p>
              </div>
            )}
            <p className="hint">
              قصه‌ها روی همین دستگاه ذخیره می‌شوند. برای اسم بچه‌ها، آن‌ها را در <a href={href('/me')}>پروفایل</a> اضافه کنید.
            </p>
          </aside>
        </div>
      </div>
    </main>
  );
}
