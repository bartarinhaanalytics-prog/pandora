import { articles } from '../data/articles.js';
import { readyStories } from '../data/readyStories.js';
import { stageById } from '../data/stages.js';
import { tipFor } from '../lib/daily.js';
import { diffDays, formatJ, fromISO, today } from '../lib/jalali.js';
import { faNum } from '../lib/fa.js';
import { ageMonths, childList } from '../lib/kids.js';
import { href } from '../lib/router.js';
import { useStored } from '../lib/store.js';
import ArticleItem from '../components/ArticleItem.jsx';
import Icon from '../components/Icon.jsx';
import { buildPlan } from './Cycle.jsx';
import { pregnancyFrom } from './Pregnancy.jsx';
import { ageText } from './Profile.jsx';
import { nextVaccine } from './Vaccines.jsx';
import './Dashboard.css';

const ACCOUNT = [
  { path: '/me/profile', label: 'پروفایل و خانواده', icon: 'User' },
  { path: '/me/stories', label: 'قصه‌های من', icon: 'BookHeart' },
  { path: '/me/saved', label: 'محتوای ذخیره‌شده', icon: 'BookOpen' },
  { path: '/me/partner', label: 'اتصال همسر', icon: 'HeartHandshake' },
  { path: '/me/settings', label: 'تنظیمات', icon: 'Sparkles' }
];

export default function Dashboard() {
  const [profile] = useStored('profile', {});
  const [settings] = useStored('settings', {});
  const [cycle] = useStored('cycle', { periods: [] });
  const [stories] = useStored('stories', []);
  const [vdone] = useStored('vaccinesDone', {});
  const stage = stageById(profile.stage);
  const kids = childList(profile);
  const preg = profile.stage === 'pregnancy' ? pregnancyFrom(fromISO(profile.lmp)) : null;
  const plan = cycle.periods?.length ? buildPlan({ cycleLen: 28, periodLen: 5, ...cycle }) : null;
  const youngest = kids.filter((k) => k.birthDate).sort((a, b) => b.birthDate - a.birthDate)[0];
  const vac = kids.map((k) => ({ k, v: nextVaccine(k, vdone) })).filter((x) => x.v).sort((a, b) => a.v.date - b.v.date)[0];
  const age = youngest ? ageMonths(youngest.birthDate) / 12 : null;
  const ageId = age === null ? '' : age < 4 ? '2-4' : age < 6 ? '4-6' : '6-8';
  const suggestion = readyStories.find((s) => s.age === ageId) || readyStories[new Date().getDate() % readyStories.length];
  const arts = stage ? articles.filter((a) => a.stage === stage.id || (a.stage === 'all' && stage.cats.includes(a.cat))).slice(0, 3) : articles.slice(0, 3);

  if (!profile.onboarded && !profile.stage) {
    return (
      <main id="main" className="page" tabIndex={-1}>
        <div className="wrap dash-empty">
          <h1>داشبورد من</h1>
          <p>سه سؤال کوتاه جواب دهید تا دردونه هفتهٔ بارداری، سن بچه، واکسن بعدی و مطالب مرحلهٔ خودتان را همین‌جا نشان دهد.</p>
          <a className="btn btn--star" href={href('/welcome')}>
            شروع کنیم
            <Icon name="ArrowLeft" size={18} />
          </a>
          <nav className="dash-acc" aria-label="حساب من">
            {ACCOUNT.map((a) => (
              <a key={a.path} href={href(a.path)}>
                <Icon name={a.icon} size={18} />
                {a.label}
              </a>
            ))}
          </nav>
        </div>
      </main>
    );
  }

  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <header className="dash-head">
          <h1>{profile.name ? `سلام ${profile.name}` : 'سلام'}</h1>
          <p className="dash-status">
            {preg ? (
              <>
                هفتهٔ <strong>{faNum(preg.week)}</strong> بارداری؛ {faNum(Math.max(0, diffDays(preg.due, today())))} روز تا زایمان
              </>
            ) : youngest ? (
              <>
                {youngest.name} <strong>{ageText(youngest.birthDate)}</strong> است
              </>
            ) : stage ? (
              <>مرحلهٔ شما: {stage.label}</>
            ) : (
              'به دردونه خوش آمدید'
            )}
          </p>
        </header>

        <div className="dash">
          <div className="stack">
            {settings.dailyTip !== false && (
              <section className="panel dash-tip" aria-labelledby="dash-tip">
                <h2 id="dash-tip" className="label">
                  پیام امروز
                </h2>
                <p className="tip-today">{tipFor(profile.stage || 'general')}</p>
                <a href={href('/tools/daily')}>پیام‌های روزهای قبل</a>
              </section>
            )}

            <section className="dash-now" aria-label="وضعیت">
              {preg && (
                <a className="now" href={href(`/pregnancy/${preg.week}`)}>
                  <Icon name="HeartPulse" size={22} />
                  <span>
                    <strong>هفتهٔ {faNum(preg.week)}</strong>
                    <small>زایمان حدود {formatJ(preg.due)}</small>
                  </span>
                </a>
              )}
              {plan && (
                <a className="now" href={href('/tools/cycle')}>
                  <Icon name="CalendarDays" size={22} />
                  <span>
                    <strong>پریود بعدی {formatJ(plan.next, false)}</strong>
                    <small>{faNum(Math.max(0, diffDays(plan.next, today())))} روز دیگر</small>
                  </span>
                </a>
              )}
              {vac && (
                <a className="now" href={href('/tools/vaccines')}>
                  <Icon name="ShieldCheck" size={22} />
                  <span>
                    <strong>
                      واکسن {vac.v.label} {vac.k.name}
                    </strong>
                    <small>حدود {formatJ(vac.v.date)}</small>
                  </span>
                </a>
              )}
              {kids.map((k) => (
                <a key={k.id} className="now" href={href('/tools/growth')}>
                  <Icon name="Baby" size={22} />
                  <span>
                    <strong>{k.name}</strong>
                    <small>{k.birthDate ? ageText(k.birthDate) : 'تاریخ تولد ثبت نشده'}</small>
                  </span>
                </a>
              ))}
            </section>

            <section aria-labelledby="dash-arts">
              <h2 id="dash-arts" className="section-title">
                {stage ? `برای مرحلهٔ ${stage.short}` : 'پیشنهاد امروز'}
              </h2>
              <div className="arts">
                {arts.map((a) => (
                  <ArticleItem key={a.id} a={a} />
                ))}
              </div>
              {stage && (
                <a className="btn btn--line" style={{ marginTop: 'var(--s-4)' }} href={href(`/stage/${stage.id}`)}>
                  همهٔ مسیر {stage.short}
                </a>
              )}
            </section>
          </div>

          <aside className="stack">
            <section className="panel stack" aria-labelledby="dash-story">
              <h2 id="dash-story" className="section-title">
                قصهٔ امشب
              </h2>
              <a className="btn btn--star" href={href('/story')}>
                <Icon name="BookHeart" size={18} />
                {kids[0] ? `ساختن قصه برای ${kids[0].name}` : 'ساختن قصه'}
              </a>
              {suggestion && (
                <a className="dash-sugg" href={href(`/stories/${suggestion.id}`)}>
                  <small>یا یک قصهٔ آماده:</small>
                  <strong>{suggestion.title}</strong>
                </a>
              )}
              <div className="actions">
                <a href={href('/me/stories')}>قصه‌های من ({faNum(stories.length)})</a>
                <a href={href('/sleep')}>روتین خواب</a>
              </div>
            </section>
            {stage && (
              <section className="panel stack" aria-labelledby="dash-tools">
                <h2 id="dash-tools" className="section-title">
                  ابزارهای شما
                </h2>
                <nav className="me-links" style={{ padding: 0 }} aria-labelledby="dash-tools">
                  {stage.tools.map((t) => (
                    <a key={t.path} href={href(t.path)}>
                      <Icon name="Sparkles" size={18} />
                      <span>
                        <strong>{t.label}</strong>
                      </span>
                    </a>
                  ))}
                </nav>
              </section>
            )}
            <nav className="panel dash-acc" aria-label="حساب من">
              {ACCOUNT.map((a) => (
                <a key={a.path} href={href(a.path)}>
                  <Icon name={a.icon} size={18} />
                  {a.label}
                </a>
              ))}
            </nav>
          </aside>
        </div>
      </div>
    </main>
  );
}
