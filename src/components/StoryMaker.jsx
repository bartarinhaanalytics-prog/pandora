import { useId, useRef, useState } from 'react';
import { heroes, makeStory } from '../data/stories.js';
import Icon from './Icon.jsx';
import './StoryMaker.css';

const MAX = 20;

/* امضای صفحه: قصهٔ امشب همین‌جا، روی لحاف ساخته می‌شود؛ بدون ثبت‌نام. */
export default function StoryMaker() {
  const id = useId();
  const [name, setName] = useState('');
  const [hero, setHero] = useState(heroes[0].id);
  const [error, setError] = useState('');
  const [story, setStory] = useState(null);
  const storyRef = useRef(null);
  const inputRef = useRef(null);

  const build = (heroId = hero) => {
    const n = name.trim();
    if (!n) {
      setError('اسم بچه را بنویسید تا قهرمان قصه شود.');
      inputRef.current?.focus();
      return;
    }
    setError('');
    const made = makeStory(n, heroId);
    setStory({ ...made, key: Date.now() });
    try {
      sessionStorage.setItem('dordooneh:story', JSON.stringify({ name: n, hero: heroId, title: made.title }));
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

  return (
    <div className="maker panel">
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

        <button type="submit" className="btn btn--star maker__go">
          <Icon name="BookHeart" size={20} />
          قصه را بساز
        </button>
      </form>

      {story && (
        <article key={story.key} className="tale" ref={storyRef} tabIndex={-1} aria-labelledby={`${id}-tale`} aria-live="polite">
          <h3 id={`${id}-tale`} className="tale__title">
            {story.title}
          </h3>
          {story.parts.map((p, i) => (
            <p key={i} style={{ '--i': i }}>
              {p}
            </p>
          ))}
          <div className="tale__actions">
            <a href="#start" className="btn btn--star">
              نگه‌داشتن قصه با ثبت‌نام
              <Icon name="ArrowLeft" size={18} />
            </a>
            <button type="button" className="btn btn--line" onClick={another}>
              <Icon name="RotateCcw" size={18} />
              یک قصهٔ دیگر
            </button>
          </div>
          <p className="tale__note">
            این قصهٔ نمونه روی همین گوشی ساخته شد و جایی فرستاده نشد. بعد از ثبت‌نام، هر شب قصهٔ تازه با موضوع و طول دلخواه می‌سازید.
          </p>
        </article>
      )}
    </div>
  );
}
