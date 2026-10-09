import { useState } from 'react';
import { href } from '../lib/router.js';
import { useStored } from '../lib/store.js';
import Icon from './Icon.jsx';
import './StoryReader.css';

/*
 * خواندن قصه در اتاق تاریک: متن درشت، تنظیم اندازه، و «نور کم» که متن را گرم‌تر و کم‌نورتر می‌کند
 * تا چشم بچه اذیت نشود. اندازهٔ پیش‌فرض از تنظیمات می‌آید.
 */
export default function StoryReader({ title, parts, back, meta, actions }) {
  const [settings, setSettings] = useStored('settings', {});
  const [size, setSize] = useState(settings.storySize || 1.25);
  const [dim, setDim] = useState(Boolean(settings.dimReading));
  const change = (v) => {
    const s = Math.min(2, Math.max(1, Math.round(v * 100) / 100));
    setSize(s);
    setSettings({ ...settings, storySize: s });
  };
  return (
    <main id="main" className={`reader ${dim ? 'reader--dim' : ''}`} tabIndex={-1} style={{ '--reader-size': `${size}rem` }}>
      <div className="reader__bar">
        <a className="reader__back" href={href(back.path)}>
          <Icon name="ChevronRight" size={20} />
          {back.label}
        </a>
        <div className="reader__tools" role="group" aria-label="تنظیم خواندن">
          <button type="button" onClick={() => change(size - 0.125)}>
            <Icon name="AArrowDown" size={20} />
            <span className="sr-only">متن کوچک‌تر</span>
          </button>
          <button type="button" onClick={() => change(size + 0.125)}>
            <Icon name="AArrowUp" size={20} />
            <span className="sr-only">متن بزرگ‌تر</span>
          </button>
          <button
            type="button"
            aria-pressed={dim}
            onClick={() => {
              setDim(!dim);
              setSettings({ ...settings, dimReading: !dim });
            }}
          >
            <Icon name="Moon" size={20} />
            <span className="reader__dim-label">نور کم</span>
          </button>
        </div>
      </div>
      <article className="reader__page" aria-labelledby="reader-title">
        <h1 id="reader-title" className="reader__title">
          {title}
        </h1>
        {meta && <p className="reader__meta">{meta}</p>}
        {parts.map((p, i) => (
          <p key={i}>{p}</p>
        ))}
        {actions && <div className="actions reader__actions">{actions}</div>}
      </article>
    </main>
  );
}
