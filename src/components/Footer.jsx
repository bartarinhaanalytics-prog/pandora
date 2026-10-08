import { footer } from '../data/content.js';
import Icon from './Icon.jsx';
import Logo from './Logo.jsx';
import './Footer.css';

export default function Footer() {
  return (
    <footer className="site-footer">
      <div className="container">
        <div className="site-footer__grid">
          <div className="site-footer__brand">
            <Logo />
            <p>همراه رایگان خانواده‌های ایرانی، از آمادگی ازدواج تا شب‌های کودکی.</p>
          </div>

          {footer.columns.map((col) => (
            <nav key={col.title} aria-label={col.title} className="site-footer__col">
              <h2 className="site-footer__title">{col.title}</h2>
              <ul role="list">
                {col.links.map((l) => (
                  <li key={l}>
                    <a href="#top">{l}</a>
                  </li>
                ))}
              </ul>
            </nav>
          ))}

          <div className="site-footer__col">
            <h2 className="site-footer__title">تماس</h2>
            <ul role="list" className="site-footer__contact">
              <li>
                <Icon name="Mail" size={18} />
                <span>{footer.contact.email}</span>
              </li>
              <li>
                <Icon name="Phone" size={18} />
                <span>{footer.contact.phone}</span>
              </li>
            </ul>
            <ul role="list" className="site-footer__social">
              {footer.social.map((s) => (
                <li key={s.name}>
                  <a href="#top" className="icon-btn" aria-label={`${s.name} دردونه ${s.handle}`}>
                    <Icon name={s.icon} size={20} />
                  </a>
                </li>
              ))}
            </ul>
          </div>
        </div>

        <div className="site-footer__bottom">
          <p>{footer.disclaimer}</p>
          <nav aria-label="قوانین" className="site-footer__legal">
            {footer.legal.map((l) => (
              <a key={l} href="#top">
                {l}
              </a>
            ))}
            <span>© ۱۴۰۵ دردونه</span>
          </nav>
        </div>
      </div>
    </footer>
  );
}
