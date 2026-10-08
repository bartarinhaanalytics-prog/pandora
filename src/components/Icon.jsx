import {
  ArrowLeft,
  BookOpenText,
  Check,
  ChevronLeft,
  ChevronRight,
  CircleAlert,
  Instagram,
  Loader2,
  Lock,
  Mail,
  Menu,
  MoonStar,
  Phone,
  Play,
  Route,
  Send,
  ShieldCheck,
  Sparkles,
  Timer,
  X
} from 'lucide-react';

const ICONS = {
  ArrowLeft,
  BookOpenText,
  Check,
  ChevronLeft,
  ChevronRight,
  CircleAlert,
  Instagram,
  Loader2,
  Lock,
  Mail,
  Menu,
  MoonStar,
  Phone,
  Play,
  Route,
  Send,
  ShieldCheck,
  Sparkles,
  Timer,
  X
};

/** آیکن تزئینی؛ برای آیکن‌های بدون متن، نام را روی دکمهٔ والد بگذارید. */
export default function Icon({ name, size = 20, strokeWidth = 1.9, className }) {
  const Cmp = ICONS[name];
  if (!Cmp) return null;
  return <Cmp size={size} strokeWidth={strokeWidth} className={className} aria-hidden="true" focusable="false" />;
}
