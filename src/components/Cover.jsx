/**
 * تصویرسازی جلد قصه و لالایی: طاق، آسمان شب، ماه و یک نقش مخصوص هر اثر.
 * لایه‌ها برای پارالاکس کنترل‌شده جدا هستند (data-depth).
 */
const MOTIFS = {
  moon: (
    <g>
      <path d="M118 236c0-20 9-36 20-36s20 16 20 36" fill="#d9dfe8" />
      <path d="M128 216c-2-14 0-24 4-24s5 10 3 24M148 216c2-14 0-24-4-24s-5 10-3 24" fill="none" stroke="#d9dfe8" strokeWidth="6" strokeLinecap="round" />
      <circle cx="170" cy="226" r="7" fill="#f0c766" />
      <circle cx="170" cy="226" r="16" fill="#f0c766" opacity="0.18" />
    </g>
  ),
  flower: (
    <g fill="#f0c766">
      {[0, 72, 144, 216, 288].map((a) => (
        <ellipse key={a} cx="150" cy="196" rx="9" ry="20" transform={`rotate(${a} 150 214)`} opacity="0.92" />
      ))}
      <circle cx="150" cy="214" r="8" fill="#fbefc9" />
      <path d="M150 222v34" stroke="#7cc4bf" strokeWidth="4" strokeLinecap="round" />
    </g>
  ),
  mountain: (
    <g>
      <path d="M70 262 128 178l26 34 18-22 44 72z" fill="#33456a" />
      <path d="m128 178 14 18-14 4-12-6z" fill="#eef1f6" opacity="0.85" />
      <circle cx="104" cy="250" r="12" fill="#8a6a4a" />
      <circle cx="96" cy="241" r="5" fill="#8a6a4a" />
      <circle cx="112" cy="241" r="5" fill="#8a6a4a" />
    </g>
  ),
  wave: (
    <g fill="none" stroke="#7cc4bf" strokeWidth="5" strokeLinecap="round">
      <path d="M78 214c18-14 36-14 54 0s36 14 54 0 36-14 54 0" />
      <path d="M78 238c18-14 36-14 54 0s36 14 54 0 36-14 54 0" opacity="0.6" />
    </g>
  ),
  star: (
    <g>
      <path d="m150 182 10 22 24 3-18 16 5 24-21-12-21 12 5-24-18-16 24-3z" fill="#f0c766" />
      <path d="M138 226q12 8 24 0" fill="none" stroke="#111a2c" strokeWidth="3" strokeLinecap="round" />
    </g>
  )
};

export default function Cover({ art, className }) {
  return (
    <svg className={className} viewBox="0 0 300 300" aria-hidden="true" focusable="false" preserveAspectRatio="xMidYMid slice">
      <g data-depth="0.2">
        <rect width="300" height="300" fill="#18233a" />
        <circle cx="60" cy="50" r="1.8" fill="#eef1f6" opacity="0.7" />
        <circle cx="240" cy="38" r="1.5" fill="#eef1f6" opacity="0.6" />
        <circle cx="262" cy="120" r="1.4" fill="#eef1f6" opacity="0.5" />
        <circle cx="34" cy="150" r="1.4" fill="#eef1f6" opacity="0.5" />
      </g>
      <g data-depth="0.5">
        <path d="M70 300V140a80 80 0 0 1 160 0v160z" fill="#22304b" />
        <circle cx="196" cy="100" r="20" fill="#f0c766" />
        <circle cx="189" cy="93" r="5.5" fill="#fbefc9" />
      </g>
      <g data-depth="0.8">{MOTIFS[art]}</g>
      <g data-depth="1">
        <path d="M0 300v-36c60-22 130-26 200-8 40 10 70 10 100 2v42z" fill="#111a2c" />
      </g>
    </svg>
  );
}
