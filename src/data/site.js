/* نقشهٔ بخش‌های سایت؛ سرصفحه، فهرست موبایل، پانویس و معرفی صفحهٔ اول از این‌جا می‌خوانند. */

export const primaryNav = [
  { path: '/health', label: 'سلامت' },
  { path: '/pregnancy', label: 'بارداری' },
  { path: '/story', label: 'قصه‌ساز' },
  { path: '/lullabies', label: 'لالایی' },
  { path: '/tools/due-date', label: 'ابزارها' },
  { path: '/community', label: 'جامعه' }
];

export const bottomNav = [
  { path: '/', label: 'خانه', icon: 'House' },
  { path: '/health', label: 'سلامت', icon: 'HeartPulse' },
  { path: '/story', label: 'قصه', icon: 'BookHeart' },
  { path: '/search', label: 'جستجو', icon: 'Search' },
  { path: '/me', label: 'من', icon: 'User' }
];

export const sitemap = [
  {
    title: 'سلامت',
    links: [
      { path: '/health', label: 'دسته‌های سلامت' },
      { path: '/stage/pregnancy', label: 'مسیر من (هر مرحله)' },
      { path: '/pregnancy', label: 'بارداری هفته‌به‌هفته' },
      { path: '/men', label: 'سلامت مردان و نقش پدر' },
      { path: '/qa', label: 'پرسش‌های سلامت' },
      { path: '/ask', label: 'پرسش بی‌نام از متخصص' }
    ]
  },
  {
    title: 'قصه و لالایی',
    links: [
      { path: '/story', label: 'قصه‌ساز' },
      { path: '/stories', label: 'قصه‌های آماده' },
      { path: '/me/stories', label: 'قصه‌های من' },
      { path: '/lullabies', label: 'کتابخانهٔ لالایی' },
      { path: '/sleep', label: 'روتین خواب کودک' }
    ]
  },
  {
    title: 'ابزارها',
    links: [
      { path: '/tools/due-date', label: 'محاسبهٔ تاریخ زایمان' },
      { path: '/tools/cycle', label: 'تقویم قاعدگی و باروری' },
      { path: '/tools/vaccines', label: 'جدول واکسن کودک' },
      { path: '/tools/growth', label: 'نمودار رشد کودک' },
      { path: '/tools/symptoms', label: 'ثبت علائم روزانه' },
      { path: '/tools/daily', label: 'پیام روزانه' }
    ]
  },
  {
    title: 'من و خانواده',
    links: [
      { path: '/me', label: 'داشبورد من' },
      { path: '/me/profile', label: 'پروفایل و خانواده' },
      { path: '/me/partner', label: 'اتصال همسر' },
      { path: '/me/saved', label: 'محتوای ذخیره‌شده' },
      { path: '/me/settings', label: 'تنظیمات' },
      { path: '/community', label: 'جامعهٔ والدین' }
    ]
  },
  {
    title: 'دردونه',
    links: [
      { path: '/about', label: 'دربارهٔ ما' },
      { path: '/privacy', label: 'حریم خصوصی' },
      { path: '/terms', label: 'قوانین و سلب مسئولیت پزشکی' },
      { path: '/contact', label: 'تماس با ما' }
    ]
  }
];
