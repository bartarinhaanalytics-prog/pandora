import { formatJ, fromISO } from '../lib/jalali.js';
import { useStored } from '../lib/store.js';
import { href } from '../lib/router.js';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';
import StoryMaker from '../components/StoryMaker.jsx';

export default function Story() {
  const [stories] = useStored('stories', []);
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
          <aside className="stack">
            <section className="panel stack" aria-labelledby="mine-title">
              <h2 id="mine-title" className="section-title">
                قصه‌های من
              </h2>
              {stories.length ? (
                <ul className="rows" style={{ listStyle: 'none', padding: 0, margin: 0 }}>
                  {stories.slice(0, 4).map((s) => (
                    <li key={s.id} className="row">
                      <a className="row__title" href={href(`/story/read/${s.id}`)}>
                        {s.title}
                      </a>
                      <span className="row__meta">{formatJ(fromISO(s.date))}</span>
                    </li>
                  ))}
                </ul>
              ) : (
                <p className="hint">بعد از ساختن قصه، دکمهٔ «ذخیره» را بزنید تا این‌جا بماند.</p>
              )}
              <a className="btn btn--line" href={href('/me/stories')}>
                همهٔ قصه‌های من
              </a>
            </section>
            <nav className="panel me-links" aria-label="قصه و خواب">
              <a href={href('/stories')}>
                <Icon name="BookOpen" size={20} />
                <span>
                  <strong>قصه‌های آماده</strong>
                  <small>برای شب‌هایی که حوصلهٔ ساختن نیست</small>
                </span>
              </a>
              <a href={href('/sleep')}>
                <Icon name="Moon" size={20} />
                <span>
                  <strong>روتین خواب کودک</strong>
                  <small>ساعت خواب مناسب سن و روال شبانه</small>
                </span>
              </a>
              <a href={href('/lullabies')}>
                <Icon name="Music" size={20} />
                <span>
                  <strong>لالایی‌ها</strong>
                  <small>وقتی قصه تمام شد</small>
                </span>
              </a>
            </nav>
            <p className="hint">
              برای اسم بچه‌ها، آن‌ها را در <a href={href('/me/profile')}>پروفایل</a> اضافه کنید.
            </p>
          </aside>
        </div>
      </div>
    </main>
  );
}
