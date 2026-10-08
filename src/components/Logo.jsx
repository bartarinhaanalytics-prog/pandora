/** لوگوی متنی دردونه: واژه و یک مروارید کوچک. */
export default function Logo() {
  return (
    <span className="logo">
      <span className="logo__word">دردونه</span>
      <svg className="logo__pearl" width="12" height="12" viewBox="0 0 12 12" aria-hidden="true" focusable="false">
        <circle cx="6" cy="6" r="5" fill="var(--c-accent)" />
        <circle cx="4.4" cy="4.4" r="1.4" fill="#fff" opacity="0.7" />
      </svg>
    </span>
  );
}
