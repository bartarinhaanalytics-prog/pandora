import { useId, useRef, useState } from 'react';
import { NIGHTLY_LIMIT, heroes, lengths, makeStory, topics } from '../data/stories.js';
import { faNum } from '../lib/fa.js';
import { toISO, today } from '../lib/jalali.js';
import { href } from '../lib/router.js';
import { read, uid, useStored, write } from '../lib/store.js';
import Icon from './Icon.jsx';
import './StoryMaker.css';

const MAX = 20;

/* شمارش قصه‌های امشب؛ شب از ظهر تا ظهر روز بعد حساب می‌شود */
function nightKey() {
  const d = new Date();
  if (d.getHours() < 12) d.setDate(d.getDate() - 1);
  return toISO(d);
}
function usedTonight() {
  const c = read('storyCount', {});
  return c.night === nightKey() ? c.n : 0;
}

/*
 * قصه‌ساز. در صفحهٔ اول ساده است (اسم، موضوع، قهرمان)؛
 * با full در صفحهٔ قصه‌ساز: انتخاب بچه از پروفایل، طول قصه، ذخیره، اندازهٔ متن و اشتراک.
 */
export default function StoryMaker({ full = false }) {
  const id = useId();
  const [profile] = useStored('profile', {});
  const kids = (profile.children || []).filter((k) => k.name);
  const [name, setName] = useState(kids[0]?.name || '');
  const [hero, setHero] = useState(heroes[0].id);
  const [topic, setTopic] = useState(topics[0].id);
  const [length, setLength] = useState('short');
  const [error, setError] = useState('');
  const [story, setStory] = useState(null);
  const [size, setSize] = useState(1);
  const [saved, setSaved] = useState(false);
  const [shareMsg, setShareMsg] = useState('');
  const storyRef = useRef(null);
  const inputRef = useRef(null);

  const build = (heroId = hero) => {
    const n = name.trim();
    if (!n) {
      setError('اسم بچه را بنویسید تا قهرمان قصه شود.');
      inputRef.current?.focus();
      return;
    }
    if (usedTonight() >= NIGHTLY_LIMIT) {
      setError(`امشب ${faNum(NIGHTLY_LIMIT)} قصه ساختید؛ فردا شب دوباره. قصه‌های ذخیره‌شده را می‌توانید دوباره بخوانید.`);
      return;
    }
    setError('');
    const made = makeStory(n, heroId, topic, length);
    write('storyCount', { night: nightKey(), n: usedTonight() + 1 });
    setStory({ ...made, key: Date.now(), name: n, hero: heroId, topic, length });
    setSaved(false);
    setShareMsg('');
    try {
      sessionStorage.setItem('dordooneh:story', JSON.stringify({ name: n, hero: heroId, topic, title: made.title }));
      window.dispatchEvent(new Event('dordooneh:story'));
    } catch {
      /* حافظهٔ مرورگر در دسترس نیست؛ قصه فقط روی صفحه می‌ماند */
    }
    requestAnimationFrame(() => storyRef.current?.focus({ preventScroll: false }));
  };

  const another = () => {
    const i = heroes.findIndex((h) => h.id === hero);
    const next = heroes[(i + 1) % heroes.length].id;
    setHero(next);
    build(next);
  };

  const save = () => {
    const list = read('stories', []);
    write('stories', [{ id: uid(), title: story.title, parts: story.parts, name: story.name, topic: story.topic, date: toISO(today()) }, ...list].slice(0, 50));
    setSaved(true);
  };

  const share = async () => {
    const text = `${story.title}\n\n${story.parts.join('\n\n')}\n\nساخته‌شده با دردونه`;
    try {
      if (navigator.share) {
        await navigator.share({ title: story.title, text });
        return;
      }
      await navigator.clipboard.writeText(text);
      setShareMsg('متن قصه کپی شد؛ می‌توانید برای پدربزرگ و مادربزرگ بفرستید.');
    } catch {
      /* کاربر اشتراک را لغو کرد */
    }
  };

  const remaining = NIGHTLY_LIMIT - usedTonight();

  return (
    <div className={`maker panel ${full ? 'maker--full' : ''}`}>
      <form
        className="maker__form"
        noValidate
        onSubmit={(e) => {
          e.preventDefault();
          build();
        }}
      >
        <h2 className="maker__title" id={`${id}-t`}>
          قصهٔ امشب
        </h2>

        <div className="maker__field">
          <label htmlFor={`${id}-name`}>اسم بچه</label>
          {full && kids.length > 0 && (
            <div className="chips" role="group" aria-label="بچه‌های من">
              {kids.map((k) => (
                <button key={k.id} type="button" className="chip" aria-pressed={name === k.name} onClick={() => setName(k.name)}>
                  {k.name}
                </button>
              ))}
            </div>
          )}
          <input
            ref={inputRef}
            id={`${id}-name`}
            className="maker__input"
            value={name}
            maxLength={MAX}
            autoComplete="off"
            placeholder="مثلاً آوا"
            aria-invalid={Boolean(error)}
            aria-describedby={error ? `${id}-err` : undefined}
            onChange={(e) => {
              setName(e.target.value);
              if (error) setError('');
            }}
          />
          {error && (
            <p id={`${id}-err`} className="maker__error" role="alert">
              <Icon name="CircleAlert" size={18} />
              {error}
            </p>
          )}
        </div>

        <fieldset className="maker__heroes">
          <legend>موضوع قصه</legend>
          <div className="maker__chips">
            {topics.map((t) => (
              <label key={t.id} className="chip">
                <input type="radio" name={`${id}-topic`} value={t.id} checked={topic === t.id} onChange={() => setTopic(t.id)} />
                <span>{t.label}</span>
              </label>
            ))}
          </div>
        </fieldset>

        <fieldset className="maker__heroes">
          <legend>قهرمان قصه</legend>
          <div className="maker__chips">
            {heroes.map((h) => (
              <label key={h.id} className="chip">
                <input type="radio" name={`${id}-hero`} value={h.id} checked={hero === h.id} onChange={() => setHero(h.id)} />
                <Icon name={h.icon} size={20} />
                <span>{h.label}</span>
              </label>
            ))}
          </div>
        </fieldset>

        {full && (
          <fieldset className="maker__heroes">
            <legend>طول قصه</legend>
            <div className="maker__chips">
              {lengths.map((l) => (
                <label key={l.id} className="chip">
                  <input type="radio" name={`${id}-len`} value={l.id} checked={length === l.id} onChange={() => setLength(l.id)} />
                  <span>{l.label}</span>
                </label>
              ))}
            </div>
          </fieldset>
        )}

        <div className="maker__go-row">
          <button type="submit" className="btn btn--star maker__go">
            <Icon name="BookHeart" size={20} />
            قصه را بساز
          </button>
          {full && <span className="hint">امشب {faNum(Math.max(0, remaining))} قصهٔ دیگر می‌توانید بسازید.</span>}
        </div>
      </form>

      {story && (
        <article
          key={story.key}
          className="tale"
          ref={storyRef}
          tabIndex={-1}
          aria-labelledby={`${id}-tale`}
          aria-live="polite"
          style={{ '--tale-size': `${1.125 * size}rem` }}
        >
          <div className="tale__top">
            <h3 id={`${id}-tale`} className="tale__title">
              {story.title}
            </h3>
            {full && (
              <div className="tale__size" role="group" aria-label="اندازهٔ متن">
                <button type="button" onClick={() => setSize((s) => Math.max(0.9, s - 0.1))}>
                  <Icon name="AArrowDown" size={20} />
                  <span className="sr-only">متن کوچک‌تر</span>
                </button>
                <button type="button" onClick={() => setSize((s) => Math.min(1.6, s + 0.1))}>
                  <Icon name="AArrowUp" size={20} />
                  <span className="sr-only">متن بزرگ‌تر</span>
                </button>
              </div>
            )}
          </div>
          {story.parts.map((p, i) => (
            <p key={i} style={{ '--i': i }}>
              {p}
            </p>
          ))}
          <div className="tale__actions">
            {full ? (
              <>
                <button type="button" className="btn btn--star" onClick={save} disabled={saved}>
                  <Icon name={saved ? 'Check' : 'BookOpen'} size={18} />
                  {saved ? 'در «قصه‌های من» ذخیره شد' : 'ذخیره در قصه‌های من'}
                </button>
                <button type="button" className="btn btn--line" onClick={share}>
                  <Icon name="Share2" size={18} />
                  فرستادن برای خانواده
                </button>
              </>
            ) : (
              <a href={href('/story')} className="btn btn--star">
                ذخیره و قصه‌های بیشتر
                <Icon name="ArrowLeft" size={18} />
              </a>
            )}
            <button type="button" className="btn btn--line" onClick={another}>
              <Icon name="RotateCcw" size={18} />
              یک قصهٔ دیگر
            </button>
          </div>
          {shareMsg && (
            <p className="success" role="status">
              <Icon name="Check" size={18} />
              {shareMsg}
            </p>
          )}
          <p className="tale__note">
            این قصه از روی الگو و روی همین دستگاه ساخته شد و جایی فرستاده نشد. قصه‌ساز هوش مصنوعی با موضوع‌های بیشتر بعد از راه‌اندازی سرور
            اضافه می‌شود.
          </p>
        </article>
      )}
    </div>
  );
}
