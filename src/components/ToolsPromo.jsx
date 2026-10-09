import { useId } from 'react';
import { MONTHS, WEEKDAYS, addDays, fromJ, monthLength, toJ, today, weekdayIndex } from '../lib/jalali.js';
import { faNum } from '../lib/fa.js';
import { href } from '../lib/router.js';
import Icon from './Icon.jsx';

/* تقویم ماه جاری، فقط برای نمایش؛ تقویم واقعی در صفحهٔ ابزار است */
function MiniMonth() {
  const t = toJ(today());
  const first = fromJ(t.y, t.m, 1);
  const cells = [...Array(weekdayIndex(first)).fill(null), ...Array.from({ length: monthLength(t.y, t.m) }, (_, i) => addDays(first, i))];
  return (
    <div className="mini-cal" aria-hidden="true">
      <p className="mini-cal__title">
        {MONTHS[t.m - 1]} {faNum(t.y)}
      </p>
      <div className="mini-cal__grid">
        {WEEKDAYS.map((d) => (
          <span key={d} className="mini-cal__wd">
            {d}
          </span>
        ))}
        {cells.map((c, i) => (
          <span key={i} className={`mini-cal__d ${c && toJ(c).d === t.d ? 'is-today' : ''}`}>
            {c ? faNum(toJ(c).d) : ''}
          </span>
        ))}
      </div>
    </div>
  );
}

export default function ToolsPromo() {
  const id = useId();
  return (
    <section className="home-sec tools" aria-labelledby={`${id}-t`}>
      <div className="wrap tools__inner">
        <div className="panel tools__cal">
          <MiniMonth />
        </div>
        <div className="home-sec__copy">
          <h2 id={`${id}-t`}>تقویم قاعدگی و باروری</h2>
          <p>روزهای پریود را با تقویم شمسی ثبت کنید؛ پریود بعدی و روزهای باروری را پیش‌بینی می‌کنیم. داده‌ها فقط مال خودتان است.</p>
          <a className="btn btn--star" href={href('/tools/cycle')}>
            <Icon name="CalendarDays" size={18} />
            باز کردن تقویم
          </a>
          <nav className="tools__more" aria-label="خانواده">
            <a href={href('/me')}>
              <Icon name="Users" size={22} />
              <span>
                <strong>پروفایل و خانواده</strong>
                <small>مرحلهٔ خودتان و بچه‌ها را اضافه کنید تا همه‌چیز برایتان تنظیم شود.</small>
              </span>
            </a>
            <a href={href('/me/partner')}>
              <Icon name="HeartHandshake" size={22} />
              <span>
                <strong>اتصال همسر</strong>
                <small>همسرتان هفتهٔ بارداری و قصه‌ها را همراه شما دنبال کند.</small>
              </span>
            </a>
          </nav>
        </div>
      </div>
    </section>
  );
}
