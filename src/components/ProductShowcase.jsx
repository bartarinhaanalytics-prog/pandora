import { useCallback, useRef, useState } from 'react';
import { product } from '../data/content.js';
import { useReducedMotion } from '../hooks/useReducedMotion.js';
import Icon from './Icon.jsx';
import PhoneScreen from './PhoneScreen.jsx';
import './ProductShowcase.css';

// زاویهٔ گوشی برای هر نما (درجه)
const ANGLES = { week: { y: 16, x: 6 }, story: { y: 0, x: 4 }, lullaby: { y: -16, x: 6 } };
const LIMIT = 24;
const FA = ['۱', '۲', '۳'];

export default function ProductShowcase() {
  const [viewId, setViewId] = useState('week');
  const [spot, setSpot] = useState(0);
  const [drag, setDrag] = useState({ x: 0, y: 0 });
  const reduced = useReducedMotion();
  const dragRef = useRef(null);
  const tabRefs = useRef([]);

  const view = product.views.find((v) => v.id === viewId);
  const base = ANGLES[viewId];
  const rotY = reduced ? 0 : Math.max(-LIMIT, Math.min(LIMIT, base.y + drag.x));
  const rotX = reduced ? 0 : Math.max(-10, Math.min(12, base.x + drag.y));

  const selectView = (id) => {
    setViewId(id);
    setSpot(0);
    setDrag({ x: 0, y: 0 });
  };

  // جابه‌جایی بین زبانه‌ها با کلیدهای جهت (در راست‌به‌چپ، چپ یعنی بعدی)
  const onTabKey = (e, i) => {
    const n = product.views.length;
    let next = null;
    if (e.key === 'ArrowLeft') next = (i + 1) % n;
    if (e.key === 'ArrowRight') next = (i - 1 + n) % n;
    if (e.key === 'Home') next = 0;
    if (e.key === 'End') next = n - 1;
    if (next === null) return;
    e.preventDefault();
    selectView(product.views[next].id);
    tabRefs.current[next]?.focus();
  };

  // چرخاندن گوشی با کشیدن (موس و لمس)
  const onPointerDown = (e) => {
    if (reduced) return;
    dragRef.current = { sx: e.clientX, sy: e.clientY, ox: drag.x, oy: drag.y };
    e.currentTarget.setPointerCapture(e.pointerId);
  };
  const onPointerMove = (e) => {
    const d = dragRef.current;
    if (!d) return;
    setDrag({ x: d.ox + (e.clientX - d.sx) / 8, y: d.oy - (e.clientY - d.sy) / 12 });
  };
  const onPointerUp = () => {
    dragRef.current = null;
  };

  // چرخاندن با صفحه‌کلید روی صحنه
  const onStageKey = useCallback(
    (e) => {
      if (reduced) return;
      const step = 5;
      if (e.key === 'ArrowLeft') setDrag((d) => ({ ...d, x: d.x - step }));
      else if (e.key === 'ArrowRight') setDrag((d) => ({ ...d, x: d.x + step }));
      else if (e.key === 'ArrowUp') setDrag((d) => ({ ...d, y: d.y + step }));
      else if (e.key === 'ArrowDown') setDrag((d) => ({ ...d, y: d.y - step }));
      else if (e.key === 'Home') setDrag({ x: 0, y: 0 });
      else return;
      e.preventDefault();
    },
    [reduced]
  );

  return (
    <section id="product" className="section product on-night" aria-labelledby="product-title">
      <div className="container product__grid">
        <div className="product__copy">
          <div className="section-head">
            <p className="eyebrow">امکانات</p>
            <h2 id="product-title" className="h2">
              هر چیزی که امشب لازم دارید، در یک صفحه
            </h2>
          </div>

          <div className="tabs" role="tablist" aria-label="نماهای محصول">
            {product.views.map((v, i) => (
              <button
                key={v.id}
                ref={(el) => (tabRefs.current[i] = el)}
                id={`tab-${v.id}`}
                type="button"
                role="tab"
                className="tabs__tab"
                aria-selected={v.id === viewId}
                aria-controls="product-panel"
                tabIndex={v.id === viewId ? 0 : -1}
                onClick={() => selectView(v.id)}
                onKeyDown={(e) => onTabKey(e, i)}
              >
                {v.label}
              </button>
            ))}
          </div>

          <div id="product-panel" role="tabpanel" aria-labelledby={`tab-${viewId}`}>
          <ol className="spots" role="list">
            {view.hotspots.map((h, i) => (
              <li key={h.id}>
                <button
                  type="button"
                  className="spot-item"
                  aria-pressed={spot === i}
                  onClick={() => setSpot(i)}
                  onMouseEnter={() => setSpot(i)}
                  onFocus={() => setSpot(i)}
                >
                  <span className="spot-item__num" aria-hidden="true">
                    {FA[i]}
                  </span>
                  <span className="spot-item__body">
                    <span className="spot-item__title">{h.title}</span>
                    <span className="spot-item__text">{h.text}</span>
                  </span>
                </button>
              </li>
            ))}
          </ol>
          </div>
        </div>

        <div className="product__stage-wrap">
          <div
            className="product__stage"
            tabIndex={0}
            role="group"
            aria-roledescription="نمایش سه‌بعدی"
            aria-label={`گوشی با صفحهٔ ${view.label}. برای چرخاندن، بکشید یا از کلیدهای جهت استفاده کنید.`}
            onKeyDown={onStageKey}
            onPointerDown={onPointerDown}
            onPointerMove={onPointerMove}
            onPointerUp={onPointerUp}
            onPointerCancel={onPointerUp}
          >
            <div className="phone" style={{ '--ry': `${rotY}deg`, '--rx': `${rotX}deg` }}>
              <div className="phone__side" aria-hidden="true" />
              <div className="phone__body">
                <div className="phone__screen" aria-hidden="true">
                  <PhoneScreen view={viewId} />
                </div>
                {view.hotspots.map((h, i) => (
                  <span
                    key={h.id}
                    className={`hotspot${spot === i ? ' is-active' : ''}`}
                    style={{ left: `${h.x}%`, top: `${h.y}%` }}
                    aria-hidden="true"
                  >
                    {FA[i]}
                  </span>
                ))}
              </div>
            </div>
            <div className="product__shadow" aria-hidden="true" />
          </div>
          <p className="product__hint">
            <Icon name="Sparkles" size={16} />
            برای چرخاندن گوشی، آن را بکشید
          </p>
        </div>
      </div>
    </section>
  );
}
