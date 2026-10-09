import { useState } from 'react';
import { stages } from '../data/stages.js';
import { recentTips } from '../lib/daily.js';
import { formatJ } from '../lib/jalali.js';
import { useStored } from '../lib/store.js';
import Draft from '../components/Draft.jsx';
import PageHead from '../components/PageHead.jsx';
import './Tools.css';

export default function Daily() {
  const [profile] = useStored('profile', {});
  const [stage, setStage] = useState(profile.stage || 'general');
  const tips = recentTips(stage, 7);
  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead title="پیام روزانه" intro="هر روز یک نکتهٔ کوتاه، مخصوص مرحله‌ای که در آن هستید." crumbs={[{ label: 'ابزارها' }, { label: 'پیام روزانه' }]} />
        <div className="chips" role="group" aria-label="مرحله" style={{ marginBottom: 'var(--s-6)' }}>
          {[...stages.map((s) => [s.id, s.short]), ['general', 'عمومی']].map(([id, label]) => (
            <button key={id} type="button" className="chip" aria-pressed={stage === id} onClick={() => setStage(id)}>
              {label}
            </button>
          ))}
        </div>
        <div className="narrow stack">
          <section className="panel stack" aria-label="پیام امروز">
            <p className="row__meta">امروز، {formatJ(tips[0].date)}</p>
            <p className="tip-today">{tips[0].text}</p>
          </section>
          <section aria-label="آرشیو">
            <h2 className="section-title">روزهای قبل</h2>
            <ul className="rows" style={{ listStyle: 'none', padding: 0, margin: 0 }}>
              {tips.slice(1).map((t) => (
                <li key={t.date.toISOString()} className="row">
                  <span className="row__meta">{formatJ(t.date)}</span>
                  <p>{t.text}</p>
                </li>
              ))}
            </ul>
          </section>
          <Draft />
        </div>
      </div>
    </main>
  );
}
