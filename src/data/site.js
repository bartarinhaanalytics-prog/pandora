/* نقشهٔ بخش‌های سایت؛ سرصفحه، فهرست موبایل، پانویس و معرفی صفحهٔ اول از این‌جا می‌خوانند. */

export const primaryNav = [
  { path: '/health', label: 'سلامت' },
  { path: '/pregnancy', label: 'بارداری' },
  { path: '/story', label: 'قصه‌ساز' },
  { path: '/lullabies', label: 'لالایی' },
  { path: '/tools/cycle', label: 'تقویم' },
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
      { path: '/lullabies', label: 'کتابخانهٔ لالایی' }
    ]
  },
  {
    title: 'ابزار و خانواده',
    links: [
      { path: '/tools/cycle', label: 'تقویم قاعدگی و باروری' },
      { path: '/me', label: 'پروفایل و خانواده' },
      { path: '/me/partner', label: 'اتصال همسر' },
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
