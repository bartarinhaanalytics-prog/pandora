import { useEffect, useRef, useState } from 'react';
import { useMotion } from '../hooks/useMotion.jsx';
import './BackdropVideo.css';

const PORTRAIT = '(max-aspect-ratio: 4/5)';

/*
 * ویدیوی پس‌زمینهٔ حلقه‌ای (۸ ثانیه، بی‌صدا).
 * فقط وقتی دیده می‌شود پخش می‌شود؛ بخش‌های پایین‌تر تا نزدیک شدن چیزی دانلود نمی‌کنند.
 */
export default function BackdropVideo({ name, portrait, eager = false, position = '50% 50%' }) {
  const ref = useRef(null);
  const box = useRef(null);
  const { playing } = useMotion();
  const [near, setNear] = useState(eager);
  const [visible, setVisible] = useState(eager);
  const [tall, setTall] = useState(() => Boolean(portrait) && typeof matchMedia !== 'undefined' && matchMedia(PORTRAIT).matches);

  useEffect(() => {
    if (!portrait) return undefined;
    const mq = matchMedia(PORTRAIT);
    const on = () => setTall(mq.matches);
    mq.addEventListener('change', on);
    return () => mq.removeEventListener('change', on);
  }, [portrait]);

  useEffect(() => {
    const el = box.current;
    if (!el || !('IntersectionObserver' in window)) {
      setNear(true);
      setVisible(true);
      return undefined;
    }
    const nearIo = new IntersectionObserver(([e]) => e.isIntersecting && setNear(true), { rootMargin: '600px 0px' });
    const visIo = new IntersectionObserver(([e]) => setVisible(e.isIntersecting), { threshold: 0.05 });
    nearIo.observe(el);
    visIo.observe(el);
    return () => {
      nearIo.disconnect();
      visIo.disconnect();
    };
  }, []);

  const file = tall && portrait ? portrait : name;

  useEffect(() => {
    const v = ref.current;
    if (!v) return;
    if (playing && visible && near) {
      v.play().catch(() => {});
    } else {
      v.pause();
    }
  }, [playing, visible, near, file]);

  return (
    <div className="backdrop" aria-hidden="true" ref={box}>
      <video
        key={`${file}-${near && playing}`}
        ref={ref}
        className="backdrop__video"
        style={{ objectPosition: position }}
        poster={`./video/${file}.webp`}
        muted
        autoPlay={playing && visible}
        loop
        playsInline
        preload={near && playing ? 'auto' : 'none'}
        tabIndex={-1}
        disablePictureInPicture
      >
        {near && playing && <source src={`./video/${file}.webm`} type="video/webm" />}
        {near && playing && <source src={`./video/${file}.mp4`} type="video/mp4" />}
      </video>
    </div>
  );
}
