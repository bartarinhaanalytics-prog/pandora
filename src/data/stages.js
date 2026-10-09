/* مرحله‌های زندگی؛ صفحهٔ «مسیر من»، شروع کار و داشبورد از این‌جا می‌خوانند. */
export const stages = [
  {
    id: 'before',
    label: 'آماده شدن برای ازدواج',
    short: 'قبل از ازدواج',
    intro: 'آزمایش‌ها، گفتگو دربارهٔ انتظارها و آشنایی با سلامت جنسی؛ قبل از شروع زندگی مشترک.',
    subs: ['آزمایش‌ها و مشاوره', 'رابطه و گفتگو', 'سلامت جنسی'],
    cats: ['premarital', 'sexual'],
    tools: [
      { path: '/tools/symptoms', label: 'ثبت علائم روزانه' },
      { path: '/ask', label: 'پرسش بی‌نام از متخصص' }
    ]
  },
  {
    id: 'trying',
    label: 'اقدام به بارداری',
    short: 'اقدام به بارداری',
    intro: 'شناختن روزهای باروری، آمادگی بدن هر دو نفر و این‌که کی باید به پزشک سر زد.',
    subs: ['روزهای باروری', 'آمادگی پیش از بارداری', 'باروری مردان'],
    cats: ['pregnancy', 'women', 'men'],
    tools: [
      { path: '/tools/cycle', label: 'تقویم قاعدگی و باروری' },
      { path: '/tools/due-date', label: 'محاسبهٔ تاریخ زایمان' },
      { path: '/tools/symptoms', label: 'ثبت علائم روزانه' }
    ]
  },
  {
    id: 'pregnancy',
    label: 'بارداری',
    short: 'بارداری',
    intro: 'هفته‌به‌هفته کنار شما؛ از اولین آزمایش تا روز زایمان، برای مادر و پدر.',
    subs: ['سه‌ماههٔ اول', 'سه‌ماههٔ دوم', 'سه‌ماههٔ سوم'],
    cats: ['pregnancy', 'sexual'],
    tools: [
      { path: '/pregnancy', label: 'بارداری هفته‌به‌هفته' },
      { path: '/tools/due-date', label: 'محاسبهٔ تاریخ زایمان' },
      { path: '/tools/symptoms', label: 'ثبت علائم روزانه' }
    ]
  },
  {
    id: 'baby',
    label: 'نوزاد (زیر یک سال)',
    short: 'نوزاد',
    intro: 'خواب، شیر، واکسن و گریه؛ سال اولی که هر روزش سؤال تازه‌ای دارد.',
    subs: ['ماه‌های اول', 'خواب و تغذیه', 'واکسن و بیماری'],
    cats: ['child'],
    tools: [
      { path: '/tools/vaccines', label: 'جدول واکسن' },
      { path: '/tools/growth', label: 'نمودار رشد' },
      { path: '/sleep', label: 'روتین خواب' }
    ]
  },
  {
    id: 'child',
    label: 'کودک (۱ تا ۶ سال)',
    short: 'کودک',
    intro: 'غذا خوردن، حرف زدن، خواب شب و قصه؛ تا روز اول مدرسه.',
    subs: ['خواب و روتین شب', 'غذا و رشد', 'رفتار و بازی'],
    cats: ['child'],
    tools: [
      { path: '/sleep', label: 'روتین خواب' },
      { path: '/tools/growth', label: 'نمودار رشد' },
      { path: '/tools/vaccines', label: 'جدول واکسن' },
      { path: '/stories', label: 'قصه‌های آماده' }
    ]
  }
];

export const stageById = (id) => stages.find((s) => s.id === id);
