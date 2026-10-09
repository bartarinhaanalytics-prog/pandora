import { qa, qaStages } from '../data/qa.js';
import { href } from '../lib/router.js';
import Draft from '../components/Draft.jsx';
import Icon from '../components/Icon.jsx';
import PageHead from '../components/PageHead.jsx';

export default function QA({ query }) {
  const stage = query.get('stage') || qaStages[0].id;
  const openId = query.get('q') || '';
  const list = qa.filter((x) => x.stage === stage);
  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead
          title="پرسش‌هایی که پرسیدنشان سخت است"
          intro="پرسش‌های رایج هر مرحله، با پاسخ ساده. اگر جواب سؤالتان این‌جا نیست، بی‌نام از متخصص بپرسید."
          crumbs={[{ label: 'سلامت', path: '/health' }, { label: 'پرسش‌های سلامت' }]}
        />
        <nav className="chips" aria-label="مرحله" style={{ marginBottom: 'var(--s-5)' }}>
          {qaStages.map((s) => (
            <a key={s.id} className="chip" href={href('/qa', { stage: s.id })} aria-current={stage === s.id ? 'true' : undefined}>
              {s.label}
            </a>
          ))}
        </nav>
        <div className="two-col">
          <div>
            {list.map((x) => (
              <details key={x.id} className="acc" open={openId === x.id || undefined}>
                <summary>
                  <span>{x.q}</span>
                  <Icon name="ChevronDown" size={20} />
                </summary>
                <div className="acc__body">
                  <p>{x.a}</p>
                  <Draft />
                </div>
              </details>
            ))}
          </div>
          <aside className="panel stack">
            <h2 className="section-title">جواب سؤالتان را پیدا نکردید؟</h2>
            <p className="hint">بدون نام و شماره بپرسید؛ متخصص همان رشته جواب می‌دهد.</p>
            <a className="btn btn--star" href={href('/ask')}>
              <Icon name="Lock" size={18} />
              پرسش بی‌نام از متخصص
            </a>
            <p className="notice notice--warn">
              <Icon name="Phone" size={18} />
              <span>
                در وضعیت اورژانسی منتظر پاسخ نمانید و با{' '}
                <a href="tel:115" className="num">
                  ۱۱۵
                </a>{' '}
                تماس بگیرید.
              </span>
            </p>
          </aside>
        </div>
      </div>
    </main>
  );
}
