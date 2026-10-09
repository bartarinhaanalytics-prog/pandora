import { href } from '../lib/router.js';

/* سرِ هر صفحه: مسیر، تیتر و یک جمله توضیح */
export default function PageHead({ title, intro, crumbs = [], children }) {
  return (
    <header className="page-head">
      <nav className="crumbs" aria-label="مسیر صفحه">
        <a href={href('/')}>خانه</a>
        {crumbs.map((c) => (
          <span key={c.label} style={{ display: 'contents' }}>
            <span aria-hidden="true">/</span>
            {c.path ? <a href={href(c.path)}>{c.label}</a> : <span aria-current="page">{c.label}</span>}
          </span>
        ))}
      </nav>
      <h1>{title}</h1>
      {intro && <p>{intro}</p>}
      {children}
    </header>
  );
}
