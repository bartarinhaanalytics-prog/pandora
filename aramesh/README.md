# قالب Aramesh — سایت دوره‌های روان‌شناسی (فارسی RTL)

قالب وردپرس اختصاصی برای وب‌سایت یک روان‌شناس و فروش دوره‌های ویدیویی. زبان بصری آرام و Premium
(سبز sage روی پس‌زمینه warm off-white)، کاملاً RTL و responsive، بر پایه **Bootstrap 5 RTL** و
**PHP 8+**، بدون وابستگی به Page Builder.

این قالب بر اساس بسته‌ی طراحی (`psychology_wordpress_handoff`) و Design Tokens آن ساخته شده است.

---

## ویژگی‌ها

- **۱۸ صفحه/الگو**: خانه، درباره، آرشیو دوره‌ها، جزئیات دوره، ورود/عضویت، انتخاب مسیر ثبت‌نام،
  ثبت‌نام داخل/خارج ایران، داشبورد، دوره‌های من، پخش‌کننده جلسه، مجله، دسته مقاله، مقاله، تماس،
  سوالات متداول، قوانین، جستجو و ۴۰۴.
- **نوع‌محتوای سفارشی**: `course` (دوره)، `lesson` (جلسه)، `testimonial` (نظر) + تاکسونومی
  `course_category` و `topic`. همه فیلدها با متاباکس بومی (بدون ACF).
- **ورود/ثبت‌نام با OTP**: شماره موبایل → کد پیامکی → ساخت/ورود حساب (username = شماره موبایل).
  کد به‌صورت hash‌شده در transient با انقضا، محدودیت ارسال مجدد و محدودیت تلاش.
- **دو مسیر ثبت‌نام**: داخل ایران (OTP + پرداخت ریالی) و خارج ایران (تلگرام + منشی، تخصیص دستی دوره).
- **مالکیت دوره (Entitlement)** و **پیشرفت یادگیری (Progress)** روی جداول اختصاصی با ایندکس مناسب.
- **پخش‌کننده ویدیوی محافظت‌شده**: بدون URL مستقیم عمومی، URL امضاشده از طریق فیلتر، واترمارک پویا،
  بدون دکمه دانلود، گزارش پیشرفت با REST.
- **سئو**: Person / Course / Article / Breadcrumb / FAQ schema، OpenGraph/Twitter، canonical،
  فهرست مطالب خودکار مقاله. در صورت فعال بودن Yoast/RankMath، خروجی متای قالب غیرفعال می‌شود.
- **تنظیمات از Customizer**: نام و مشخصات دکتر، اطلاعات تماس، تلگرام/اینستاگرام/یوتیوب، لوگو،
  حالت OTP و حالت پرداخت. هیچ اطلاعات تماسی در کد hard-code نشده است.

---

## نصب

۱. پوشه `aramesh` را در مسیر `wp-content/themes/` آپلود کنید
   (یا آن را zip کرده و از **پیشخوان » نمایش » پوسته‌ها » افزودن » بارگذاری** نصب کنید).

۲. از **پیشخوان » نمایش » پوسته‌ها**، قالب **Aramesh** را فعال کنید.
   با فعال‌سازی به‌صورت خودکار:
   - جداول `wp_aramesh_entitlements` و `wp_aramesh_lesson_progress` ساخته می‌شود.
   - همه صفحات موردنیاز با الگوی درست ایجاد و «صفحه اصلی» و «صفحه مطالب (مجله)» تنظیم می‌شود.
   - یک «منوی اصلی» ساخته و به محل منوی اصلی متصل می‌شود.

۳. از **تنظیمات » پیوندهای یکتا**، ساختار را روی **نام نوشته** (`/%postname%/`) بگذارید و ذخیره کنید
   (برای فعال شدن مسیرهای `/courses/…` و `/learn/{course}/{lesson}`).

۴. از **سفارشی‌سازی**، بخش‌های «مشخصات دکتر و برند» و «اطلاعات تماس و شبکه‌ها» را پر کنید.

### محتوای نمونه (اختیاری)

از **ابزارها » محتوای نمونه Aramesh** روی دکمه بزنید تا چند دوره، جلسه، مقاله و نظر نمونه ساخته شود
و بتوانید همه صفحات و جریان‌ها را ببینید. این عملیات idempotent است.

---

## پیکربندی یکپارچه‌سازی‌ها (تولید)

قالب به‌صورت پیش‌فرض در حالت توسعه کار می‌کند. برای تولید، این نقاط اتصال (hook) را پیاده کنید —
ترجیحاً در یک **افزونه‌ی همراه (companion plugin)** یا child theme:

### ۱) ارسال پیامک OTP
```php
add_filter( 'aramesh_send_otp_sms', function ( $handled, $mobile, $code ) {
    // فراخوانی API پنل پیامک شما …
    return true; // موفق
}, 10, 3 );
```
در **سفارشی‌سازی » یکپارچه‌سازی OTP و پرداخت**، «ارائه‌دهنده پیامک» را روی «ارائه‌دهنده واقعی» بگذارید.

### ۲) درگاه پرداخت ریالی
```php
add_filter( 'aramesh_payment_gateway_url', function ( $url, $course_id, $user_id ) {
    // ساخت تراکنش و بازگرداندن URL درگاه …
    return $gateway_redirect_url;
}, 10, 3 );
```
پس از تایید پرداخت (callback درگاه)، مالکیت دوره را بدهید:
```php
aramesh_grant_course( $user_id, $course_id, 'gateway', $order_ref );
```
> جایگزین: می‌توانید از WooCommerce استفاده کنید و در `woocommerce_order_status_completed`
> تابع `aramesh_grant_course()` را برای دوره‌ی مرتبط با محصول صدا بزنید.

### ۳) ویدیوی امن (HLS/DASH یا سرویس streaming)
```php
add_filter( 'aramesh_signed_playback_url', function ( $url, $video_id, $provider, $lesson_id, $user_id ) {
    // ساخت URL امضاشده/موقت از CDN یا سرویس امن …
    return $signed_url; // مثلاً یک .m3u8 با توکن انقضادار
}, 10, 5 );
```
شناسه امن ویدیو را در متاباکس هر «جلسه» (فیلد «شناسه/مسیر امن ویدیو») وارد کنید و **هرگز** URL مستقیم
MP4 عمومی قرار ندهید. برای پخش HLS در مرورگرهای غیر Safari، اسکریپت `hls.js` را در سایت اضافه کنید
(پخش‌کننده در صورت وجود `window.Hls` از آن استفاده می‌کند).

### ۴) تخصیص دستی دوره (کاربران خارج ایران)
منشی از پیشخوان کاربر را می‌سازد و سپس:
```php
aramesh_grant_course( $user_id, $course_id, 'manual' );
```

### نقاط اتصال دیگر
- `aramesh_new_lead( $mobile )` — پس از ثبت شماره در فرم خبرنامه.
- `aramesh_contact_submitted( $data )` — پس از ارسال فرم تماس.
- `aramesh_course_granted`, `aramesh_user_registered` — رویدادهای مالکیت/ثبت‌نام.
- فیلترهای `aramesh_home_faqs`, `aramesh_faq_groups`, `aramesh_legal_tabs` برای محتوای این صفحات.

---

## فونت فارسی

به‌دلیل مسائل مجوز، فایل فونت داخل قالب نیست. برای بهترین نتیجه یکی از فونت‌های دارای مجوز
(**Peyda** یا **IRANSansX**) را در `assets/fonts/` قرار دهید و بلوک `@font-face` را در
`assets/css/fonts.css` از حالت کامنت خارج کنید. تا آن زمان از فونت سیستم و Tahoma استفاده می‌شود.

---

## ساختار

```
aramesh/
  style.css, functions.php
  header.php, footer.php, sidebar.php, searchform.php, comments.php
  front-page.php, page-about.php, archive-course.php, single-course.php
  page-login.php, page-registration-path.php, page-register-iran.php, page-register-international.php
  page-dashboard.php, page-my-courses.php, single-lesson.php
  home.php, category.php, single.php, taxonomy-course_category.php, taxonomy-topic.php
  page-contact.php, page-faq.php, page-legal.php, search.php, 404.php, index.php
  template-parts/  (course-card, article-card, otp-form, account-nav)
  assets/css  assets/js  assets/images  assets/fonts
  inc/
    setup.php, enqueue.php, post-types.php, taxonomies.php, meta-boxes.php,
    theme-options.php, template-functions.php, auth-otp.php, entitlements.php,
    progress.php, video.php, seo.php, demo-content.php
```

## نیازمندی‌ها
- WordPress 6.2+
- PHP 8.0+
- برای تولید: پنل پیامک، درگاه پرداخت و سرویس ویدیوی امن (از طریق hookهای بالا)

## امنیت ویدیو — نکته مهم
هیچ راه وبی جلوی ضبط صفحه را ۱۰۰٪ نمی‌گیرد و قالب چنین ادعایی نمی‌کند. راهکار: streaming امن،
URL امضاشده/موقت، واترمارک پویا، نبود دکمه دانلود و محدودیت دسترسی. جزوه/تمرین قابل دانلود است، ویدیو نه.

## توسعه
قالب آماده‌ی child theme است. برای سفارشی‌سازی، child theme بسازید یا از hookها و فیلترهای بالا
استفاده کنید؛ فایل‌های هسته را مستقیم تغییر ندهید.
