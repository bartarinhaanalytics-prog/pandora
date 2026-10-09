import { useId, useRef, useState } from 'react';
import { healthStages } from '../data/content.js';
import Icon from './Icon.jsx';
import './Health.css';

export default function Health() {
  const id = useId();
  const [active, setActive] = useState(healthStages[0].id);
  const tabs = useRef([]);

  const onKey = (e, i) => {
    const dir = e.key === 'ArrowLeft' ? 1 : e.key === 'ArrowRight' ? -1 : 0; // راست‌به‌چپ
    if (!dir && e.key !== 'Home' && e.key !== 'End') return;
    e.preventDefault();
    let n = i + dir;
    if (e.key === 'Home') n = 0;
    if (e.key === 'End') n = healthStages.length - 1;
    n = (n + healthStages.length) % healthStages.length;
    setActive(healthStages[n].id);
    tabs.current[n]?.focus();
  };

  const stage = healthStages.find((s) => s.id === active);

  return (
    <section id="health" className="health" aria-labelledby="health-title">
      <div className="wrap health__inner">
        <div className="health__intro">
          <h2 id="health-title">پرسش‌هایی که پرسیدنشان سخت است</h2>
          <p>
            بدون ثبت‌نام و بی‌نام بخوانید. پاسخ‌ها به زبان ساده‌اند و پیش از انتشار، متخصص همان رشته بازبینی‌شان می‌کند؛ نام او و تاریخ
            بازبینی زیر هر پاسخ می‌آید.
          </p>
        </div>

        <div className="health__panel panel">
          <div className="stages" role="tablist" aria-label="مرحلهٔ زندگی">
            {healthStages.map((s, i) => (
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
            {stage.items.map((item) => (
              <details key={item.q} className="qa__item">
                <summary>
                  <span>{item.q}</span>
                  <Icon name="ChevronDown" size={20} className="qa__chev" />
                </summary>
                <div className="qa__body">
                  <p>{item.a}</p>
                  <p className="qa__review">
                    <Icon name="Stethoscope" size={18} />
                    پیش‌نویس؛ هنوز پزشک این پاسخ را بازبینی نکرده است.
                  </p>
                </div>
              </details>
            ))}
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
