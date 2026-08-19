# منابع اسکیل‌های نصب‌شده

اسکیل‌های زیر از مخازن عمومی گیت‌هاب گرفته و بدون تغییر محتوایی در این ریپو کپی شده‌اند
(تنها استثنا در جدول ذکر شده است). هرکدام لایسنس مخزن مبدأ را حفظ می‌کنند.

| اسکیل | مخزن مبدأ | مسیر در مبدأ | لایسنس |
|---|---|---|---|
| `seo` | agricidaniel/claude-seo | `skills/seo` | MIT |
| `seo-cluster` | agricidaniel/claude-seo | `skills/seo-cluster` | MIT |
| `seo-drift` | agricidaniel/claude-seo | `skills/seo-drift` | MIT |
| `seo-page` | agricidaniel/claude-seo | `skills/seo-page` | MIT |
| `seo-review` | leonardomso/33-js-concepts | `.claude/skills/seo-review` | MIT |
| `seo-geo` | resciencelab/opc-skills | `.agents/skills/seo-geo` | Apache-2.0 |
| `seo-optimize` | davila7/claude-code-templates | `cli-tool/components/skills/development/seo` | MIT |
| `ai-seo` | coreyhaines31/marketingskills | `skills/ai-seo` | MIT |

## تغییر اعمال‌شده

`davila7/claude-code-templates` اسکیل خود را `seo` نام‌گذاری کرده بود که با اسکیل `seo`
از `agricidaniel/claude-seo` تداخل داشت. برای همین با نام `seo-optimize` نصب شد و فقط
فیلد `name` در frontmatter آن تغییر کرد؛ بقیه‌ی محتوا دست‌نخورده است.

## نکات

- `seo-geo` چند اسکریپت پایتون در `scripts/` دارد که برای کار کردن به کلید DataForSEO
  نیاز دارند؛ بدون کلید، بخش‌های تحلیل داده‌محورش کار نمی‌کند.
- بعضی از این اسکیل‌ها به اسکیل‌های دیگری از مخزن خودشان ارجاع می‌دهند
  (مثلاً `ai-seo` به `seo-audit` و `schema`) که نصب نشده‌اند؛ آن ارجاع‌ها بی‌اثرند.
- محتوای این اسکیل‌ها دستورالعمل‌های شخص ثالث است. قبل از اجرای دستورهایی که به
  سرویس بیرونی وصل می‌شوند یا فایل تغییر می‌دهند، خروجی‌شان را بررسی کنید.
