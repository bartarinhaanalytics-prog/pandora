import { nav } from '../data/content.js';
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
        <nav aria-label="پیوندهای پایین صفحه" className="footer__nav">
          {nav.map((n) => (
            <a key={n.id} href={`#${n.id}`}>
              {n.label}
            </a>
          ))}
          <a href="#start">ثبت‌نام</a>
        </nav>
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
