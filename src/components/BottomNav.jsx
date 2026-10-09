import { href } from '../lib/router.js';
import { bottomNav } from '../data/site.js';
import Icon from './Icon.jsx';
import './BottomNav.css';

/* نوار پایین گوشی: یک دست، در اتاق تاریک */
export default function BottomNav({ path }) {
  return (
    <nav className="bnav" aria-label="ناوبری اصلی">
      {bottomNav.map((n) => {
        const active = n.path === '/' ? path === '/' : path === n.path || path.startsWith(`${n.path}/`);
        return (
          <a key={n.path} href={href(n.path)} aria-current={active ? 'page' : undefined}>
            <Icon name={n.icon} size={22} />
            <span>{n.label}</span>
          </a>
        );
      })}
    </nav>
  );
}
