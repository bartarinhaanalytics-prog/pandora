import { href } from '../lib/router.js';
import Icon from './Icon.jsx';

/* انتخاب بچه از پروفایل؛ اگر بچه‌ای ثبت نشده، راه افزودنش را نشان می‌دهد */
export default function ChildPicker({ kids, value, onChange, need = 'birth' }) {
  if (!kids.length) {
    return (
      <div className="notice">
        <Icon name="Baby" size={18} />
        <span>
          اول بچه‌تان را با تاریخ تولد در <a href={href('/me/profile')}>پروفایل</a> اضافه کنید.
        </span>
      </div>
    );
  }
  return (
    <div className="chips" role="group" aria-label="بچه">
      {kids.map((k) => (
        <button key={k.id} type="button" className="chip" aria-pressed={value === k.id} onClick={() => onChange(k.id)}>
          {k.name}
          {need === 'birth' && !k.birth && ' (بدون تاریخ تولد)'}
        </button>
      ))}
    </div>
  );
}
