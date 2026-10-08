import Icon from './Icon.jsx';

/** محتوای تصویری صفحهٔ گوشی؛ معادل متنی آن در فهرست نقاط راهنما آمده است. */
export default function PhoneScreen({ view }) {
  if (view === 'story') {
    return (
      <div className="ps ps--night">
        <p className="ps__label">قصهٔ امشب</p>
        <p className="ps__title">برای آوا</p>
        <div className="ps__chips">
          <span className="ps__chip is-on">خرگوش</span>
          <span className="ps__chip">خرس</span>
          <span className="ps__chip">ستاره</span>
        </div>
        <div className="ps__chips">
          <span className="ps__chip">دوستی</span>
          <span className="ps__chip is-on">ترس از تاریکی</span>
        </div>
        <div className="ps__seg">
          <span>۳ دقیقه</span>
          <span className="is-on">۵ دقیقه</span>
          <span>۱۰ دقیقه</span>
        </div>
        <div className="ps__story">
          <p className="ps__story-title">آوا و فانوس ماه</p>
          <p>یک شب، خرگوش کوچولویی با فانوسی به اندازهٔ یک گردو پشت پنجرهٔ آوا ایستاد…</p>
        </div>
        <span className="ps__cta">قصه را بساز</span>
      </div>
    );
  }

  if (view === 'lullaby') {
    return (
      <div className="ps ps--night">
        <div className="ps__cover">
          <svg viewBox="0 0 200 140" aria-hidden="true">
            <rect width="200" height="140" fill="#22304b" />
            <circle cx="130" cy="56" r="22" fill="#f0c766" />
            <circle cx="123" cy="49" r="6" fill="#fbefc9" />
            <path d="M0 140v-26c50-18 100-18 150-4s34 10 50 4v26z" fill="#18233a" />
          </svg>
        </div>
        <p className="ps__title">لالا لالا گل پونه</p>
        <p className="ps__label">لالایی قدیمی</p>
        <div className="ps__bar">
          <span style={{ width: '38%' }} />
        </div>
        <div className="ps__lyrics">
          <p>لالا لالا گل پونه</p>
          <p className="is-dim">گدا اومد در خونه</p>
        </div>
        <div className="ps__player">
          <span className="ps__round">
            <Icon name="Timer" size={14} />
          </span>
          <span className="ps__play">
            <Icon name="Play" size={18} />
          </span>
          <span className="ps__round">۳۰</span>
        </div>
      </div>
    );
  }

  return (
    <div className="ps">
      <p className="ps__label">سلام مریم</p>
      <div className="ps__week">
        <span className="ps__week-tag">بارداری، سه‌ماههٔ دوم</span>
        <span className="ps__week-num">۱۸</span>
        <span className="ps__week-sub">هفته و ۱ روز</span>
        <span className="ps__progress">
          <span style={{ width: '45%' }} />
        </span>
      </div>
      <div className="ps__fruit">
        <svg viewBox="0 0 40 40" aria-hidden="true">
          <circle cx="20" cy="22" r="13" fill="#c9433e" />
          <path d="M16 10h8l-2 4h-4z" fill="#e7a39a" />
          <circle cx="15" cy="18" r="3" fill="#fff" opacity="0.35" />
        </svg>
        <span>اندازهٔ یک انار</span>
      </div>
      <p className="ps__label">برای هفتهٔ ۱۸ شما</p>
      <div className="ps__row" />
      <div className="ps__row ps__row--short" />
      <div className="ps__row" />
      <div className="ps__tabbar">
        <span />
        <span />
        <span />
        <span />
      </div>
    </div>
  );
}
