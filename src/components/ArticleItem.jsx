import { useEffect, useRef } from 'react';
import { faNum } from '../lib/fa.js';
import Draft from './Draft.jsx';
import Icon from './Icon.jsx';

/* یک مقاله به‌شکل آکاردئون: خلاصه همیشه پیدا، متن کامل با باز کردن */
export default function ArticleItem({ a, open = false, catLabel }) {
  const ref = useRef(null);
  useEffect(() => {
    if (open && ref.current) {
      ref.current.open = true;
      ref.current.scrollIntoView({ block: 'start' });
    }
  }, [open]);
  return (
    <details className="acc art" id={`a-${a.id}`} ref={ref}>
      <summary>
        <span className="art__sum">
          <span className="art__title">{a.title}</span>
          <span className="row__meta">
            {catLabel && <span className="tag">{catLabel}</span>}
            <span>{faNum(a.minutes)} دقیقه مطالعه</span>
          </span>
          <span className="art__summary">{a.summary}</span>
        </span>
        <Icon name="ChevronDown" size={20} />
      </summary>
      <div className="acc__body">
        {a.body.map((p) => (
          <p key={p}>{p}</p>
        ))}
        <div className="art__doctor">
          <h3>کی به پزشک مراجعه کنم</h3>
          <p>{a.doctor}</p>
        </div>
        <Draft />
      </div>
    </details>
  );
}
