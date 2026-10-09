import { useId, useRef, useState } from 'react';
import { categories } from '../data/articles.js';
import { qa, qaStages } from '../data/qa.js';
import { href } from '../lib/router.js';
import Draft from './Draft.jsx';
import Icon from './Icon.jsx';
import './Health.css';

/* صفحهٔ اول: دسته‌های سلامت + نمونهٔ پرسش‌ها + پرسش بی‌نام */
export default function Health() {
  const id = useId();
  const [active, setActive] = useState(qaStages[0].id);
  const tabs = useRef([]);
  const onKey = (e, i) => {
    const dir = e.key === 'ArrowLeft' ? 1 : e.key === 'ArrowRight' ? -1 : 0;
    if (!dir && e.key !== 'Home' && e.key !== 'End') return;
    e.preventDefault();
    let n = i + dir;
    if (e.key === 'Home') n = 0;
    if (e.key === 'End') n = qaStages.length - 1;
    n = (n + qaStages.length) % qaStages.length;
    setActive(qaStages[n].id);
    tabs.current[n]?.focus();
  };
  const items = qa.filter((x) => x.stage === active).slice(0, 3);

  return (
    <section id="health" className="health" aria-labelledby="health-title">
      <div className="wrap health__inner">
        <div className="health__intro">
          <h2 id="health-title">پرسش‌هایی که پرسیدنشان سخت است</h2>
          <p>بدون ثبت‌نام و بی‌نام بخوانید؛ به زبان ساده، با این توضیح که کی باید به پزشک بروید.</p>
          <nav className="health__cats" aria-label="دسته‌های سلامت">
            {categories.map((c) => (
              <a key={c.id} href={href(c.id === 'men' ? '/men' : `/health/${c.id}`)}>
                <span>{c.label}</span>
                <Icon name="ChevronLeft" size={18} />
              </a>
            ))}
          </nav>
        </div>

        <div className="health__panel panel">
          <div className="stages" role="tablist" aria-label="مرحلهٔ زندگی">
            {qaStages.map((s, i) => (
              <button
                key={s.id}
                ref={(el) => (tabs.current[i] = el)}
                type="button"
                role="tab"
                id={`${id}-tab-${s.id}`}
                aria-selected={active === s.id}
                aria-controls={`${id}-panel`}
                tabIndex={active === s.id ? 0 : -1}
                className="stages__tab"
                onClick={() => setActive(s.id)}
                onKeyDown={(e) => onKey(e, i)}
              >
                {s.label}
              </button>
            ))}
          </div>
          <div role="tabpanel" id={`${id}-panel`} aria-labelledby={`${id}-tab-${active}`} className="qa">
            {items.map((item) => (
              <details key={item.id} className="qa__item">
                <summary>
                  <span>{item.q}</span>
                  <Icon name="ChevronDown" size={20} className="qa__chev" />
                </summary>
                <div className="qa__body">
                  <p>{item.a}</p>
                  <Draft />
                </div>
              </details>
            ))}
          </div>
          <div className="actions health__actions">
            <a className="btn btn--line" href={href('/qa', { stage: active })}>
              همهٔ پرسش‌ها
            </a>
            <a className="btn btn--line" href={href('/ask')}>
              <Icon name="Lock" size={18} />
              پرسش بی‌نام از متخصص
            </a>
          </div>
          <p className="health__emergency">
            <Icon name="Phone" size={18} />
            <span>
              این پاسخ‌ها جای معاینهٔ پزشک را نمی‌گیرند. در وضعیت اورژانسی با{' '}
              <a href="tel:115" className="num">
                ۱۱۵
              </a>{' '}
              تماس بگیرید.
            </span>
          </p>
        </div>
      </div>
    </section>
  );
}
