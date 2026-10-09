import Icon from './Icon.jsx';

export default function Draft({ children = 'پیش‌نویس؛ هنوز پزشک این متن را بازبینی نکرده است.' }) {
  return (
    <p className="draft">
      <Icon name="Stethoscope" size={18} />
      {children}
    </p>
  );
}
