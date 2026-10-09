/* نشانه: مروارید روی بته‌جقه، همراه نام با لاله‌زار */
export default function Logo() {
  return (
    <span className="logo">
      <svg className="logo__mark" viewBox="0 0 32 32" width="32" height="32" aria-hidden="true">
        <path d="M15 28c-6.6 0-10.5-4.4-10.5-9.6 0-6.4 5.4-11 13-11 5.9 0 9.8 3 11.4 6.7-1.4 7.9-6.4 13.9-13.9 13.9Z" fill="#932425" />
        <path d="M26.6 9.6c1.2-2.4 1-4.8-.2-6.6-.4 2.3-1.7 3.7-3.4 4.6" fill="none" stroke="#f3ad3d" strokeWidth="1.8" strokeLinecap="round" />
        <circle cx="15.6" cy="18.4" r="5.2" fill="#f6ead6" />
        <circle cx="13.9" cy="16.7" r="1.6" fill="#fff" opacity=".9" />
      </svg>
      <span className="logo__word">دردونه</span>
    </span>
  );
}
