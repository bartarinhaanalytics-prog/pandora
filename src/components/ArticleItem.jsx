import { faNum } from '../lib/fa.js';
import { href } from '../lib/router.js';
import Icon from './Icon.jsx';

/* یک ردیف مقاله که به صفحهٔ کامل مقاله می‌رود */
export default function ArticleItem({ a, catLabel }) {
  return (
    <a className="art-row" href={href(`/article/${a.id}`)}>
      <span className="art__sum">
        <span className="art__title">{a.title}</span>
        <span className="row__meta">
          {catLabel && <span className="tag">{catLabel}</span>}
          <span>{faNum(a.minutes)} دقیقه مطالعه</span>
        </span>
        <span className="art__summary">{a.summary}</span>
      </span>
      <Icon name="ChevronLeft" size={20} />
    </a>
  );
}
