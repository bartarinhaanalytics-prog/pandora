import { dailyTips } from '../data/tools.js';
import { addDays, diffDays, today } from './jalali.js';

/* پیام هر روز ثابت است: بر اساس شمارهٔ روز و مرحله انتخاب می‌شود */
export function tipFor(stage, date = today()) {
  const list = dailyTips[stage] || dailyTips.general;
  const n = diffDays(date, new Date(2024, 0, 1, 12));
  return list[((n % list.length) + list.length) % list.length];
}

export const recentTips = (stage, count = 7) => Array.from({ length: count }, (_, i) => ({ date: addDays(today(), -i), text: tipFor(stage, addDays(today(), -i)) }));
