import { toISO, today } from '../lib/jalali.js';
import { useStored } from '../lib/store.js';
import Icon from './Icon.jsx';

/* نشانه‌گذاری مقاله، لالایی یا قصهٔ آماده در «محتوای ذخیره‌شده» */
export default function SaveButton({ type, id, title }) {
  const [saved, setSaved] = useStored('saved', []);
  const on = saved.some((s) => s.type === type && s.id === id);
  return (
    <button
      type="button"
      className={`btn ${on ? 'btn--star' : 'btn--line'}`}
      aria-pressed={on}
      onClick={() => setSaved(on ? saved.filter((s) => !(s.type === type && s.id === id)) : [{ type, id, title, date: toISO(today()) }, ...saved])}
    >
      <Icon name={on ? 'Check' : 'BookOpen'} size={18} />
      {on ? 'ذخیره شد' : 'ذخیره'}
    </button>
  );
}
