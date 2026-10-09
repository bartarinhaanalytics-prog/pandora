import { href } from '../lib/router.js';
import PageHead from '../components/PageHead.jsx';

export default function NotFound() {
  return (
    <main id="main" className="page" tabIndex={-1}>
      <div className="wrap">
        <PageHead title="این صفحه پیدا نشد" intro="شاید نشانی اشتباه نوشته شده یا صفحه جابه‌جا شده است." crumbs={[{ label: 'صفحهٔ پیدانشده' }]} />
        <div className="actions">
          <a className="btn btn--star" href={href('/')}>
            صفحهٔ اول
          </a>
          <a className="btn btn--line" href={href('/search')}>
            جستجو
          </a>
          <a className="btn btn--line" href={href('/story')}>
            قصه‌ساز
          </a>
        </div>
      </div>
    </main>
  );
}
