import { href } from '../lib/router.js';
import { sitemap } from '../data/site.js';
import Logo from './Logo.jsx';
import './Footer.css';

export default function Footer() {
  return (
    <footer className="footer">
      <div className="wrap footer__inner">
        <div className="footer__brand">
          <Logo />
          <p>همراه رایگان خانواده‌های ایرانی، از آمادگی ازدواج تا قصهٔ شب کودک.</p>
        </div>
        {sitemap.map((g) => (
          <nav key={g.title} aria-label={g.title} className="footer__col">
            <h2>{g.title}</h2>
            {g.links.map((l) => (
              <a key={l.path} href={href(l.path)}>
                {l.label}
              </a>
            ))}
          </nav>
        ))}
        <p className="footer__note">
          محتوای سلامت دردونه جایگزین معاینه و تشخیص پزشک نیست. در وضعیت اورژانسی با{' '}
          <a href="tel:115" className="num">
            ۱۱۵
          </a>{' '}
          تماس بگیرید.
        </p>
      </div>
    </footer>
  );
}
